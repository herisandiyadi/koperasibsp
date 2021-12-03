<?php

namespace App\Http\Controllers\API\Auth;

use App\ConfigDepositMembers;
use App\Deposit;
use App\DepositTransactionDetail;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\LoadController;
use App\Member;
use App\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\DepositTransaction;
use App\TsLoans;
use Illuminate\Support\Facades\Input;

class AuthController extends LoadController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => true, 'error_message' => trans('auth.failed')], 401);
        }
        return $this->respondWithToken($token, request("os_token"));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure. Add more required data/info here
     *
     * @param string $token
     *
     * @param string $os_token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $os_token = null)
    {

        $user = User::authTokenFormat($token, $os_token);
        return response()->json($user);
    }

	public function store(Request $request)
	{
		$input                 = Input::all();
		$getPosition           = Position::where('name', 'like', '%'.$input['jabatan'].'%')->first();
		$getPokok              = Deposit::where('deposit_name', 'like', '%pokok%')->first();
		$find                  = User::where('email', $input['email'])->count();

		return $find;
		$findM                 = Member::where('nik_bsp', $input['nik'])->count();

		// $faker 				   = Faker::create();
		if($find  != null || $findM != null){
			if($find){
				$status        = 'email';
				$msg           = 'Maaf, alamat email  yang anda masukkan sudah dipakai sebelumnya.';
			}else{
				$status        = 'nik_bsp';
				$msg           = 'Maaf, NIK Koperasi  yang anda masukkan sudah dipakai sebelumnya.';
			}
			$data              =  array(
				'status' => "failed",
				'msg'   => $msg,
				'info' => $status
			);
			return response()->json($data);
		}else{
			// insert into table user
			$userMax           = User::where('username', 'like', '%KSBSP%')->orderBy('username', 'DESC')->first();

			if($userMax        == null){
				$username      = "KSBSP-0000001";
			}else{
				$expNum        = explode('-', $userMax->username);
				$username      = 'KSBSP-'.str_pad($expNum[1] + 1, 7, 0, STR_PAD_LEFT);
			}


			$user              = new User();
			$user->id          = $user::max('id')+1;
			$user->name        = $input['nama'];
			$user->email       = $input['email'];
			$user->username    = $username;
			// send start email
			$this->sendEmail($user->username, $input['email'], $input['password']);
			// send end   email
			$user->password    = $input['password'];
			$user->position_id = $getPosition->id;
			$user->save();
			//assign role based on position->level
			$user->assignRole($getPosition->level->name);

			// insert into table member
			$expName               = explode(' ', $input['nama']);
			$member                = new Member();
			$member->id            = $member::max('id')+1;
			$member->nik_bsp       = $input['nik'];
			$member->nik_koperasi  = $input['nik'];
			if(count($expName) > 1 ){
				$member->first_name           = $expName[0];
				if(isset($expName[2])        != null){
					$member->last_name            = $expName[1].' '.$expName[2];
				} elseif(isset($expName[3])  != null){
					$member->last_name            = $expName[1].' '.$expName[2].' '.$expName[3];
				} elseif (isset($expName[4]) != null) {
					$member->last_name            = $expName[1].' '.$expName[2].' '.$expName[3].' '.$expName[4];
				} else{
					$member->last_name            = $expName[1];
				}
			}
			$member->nik                      = $input['nik'];
			$member->user_id                  = $user->id;
			$member->email                    = $input['email'];
			$member->position_id              = $getPosition->id;
			// $member->region_id                = $input['region_id'];
			// $member->project_id               = $input['project_id'];
			// if(isset($input['branch_id'])){
			// 	$member->branch_id  = $input['branch_id'];
			// }else{
			// 	$member->branch_id  = null;
			// }
			$member->save();
			// insert into table deposit
			$ts_deposits                      = new DepositTransaction();
			$ts_deposits->id                  = $ts_deposits::max('id')+1;
			$ts_deposits->member_id           = $member->id;
			$ts_deposits->ms_deposit_id       = $getPokok->id;
			$ts_deposits->deposit_number      = rand();
			$ts_deposits->total_deposit       = $input['potong'] + $getPokok->deposit_minimal + $input['sukarela'];
			$ts_deposits->post_date           = new \DateTime();
			$ts_deposits->save();
			// insert into table log deposit
			$callGlobal                       = new GlobalController();
			$totalLy                          = $input['potong'] + $getPokok->deposit_minimal + $input['sukarela'];
			$callGlobal->logDeposit('register', $member->id, $totalLy, 0, $totalLy);


			$this->configDeposit($member->id, 'wajib', $input['potong']);
			// insert into table deposit detail
			if($input['pemotongan'] == 1){
				$ts_deposit_detail                  = new DepositTransactionDetail();
				$ts_deposit_detail->id              = $ts_deposit_detail::max('id')+1;
				$ts_deposit_detail->transaction_id  = $ts_deposits->id;
				$ts_deposit_detail->deposits_type   = 'pokok';
				$ts_deposit_detail->debit           = 0;
				$ts_deposit_detail->credit          = $input['potong'] + $getPokok->wajib + $getPokok->deposit_minimal + $input['sukarela'];
				$ts_deposit_detail->total           = $input['potong'] + $getPokok->wajib + $getPokok->deposit_minimal + $input['sukarela'];
				$ts_deposit_detail->save();
				$this->configDeposit($member->id, 'pokok', $getPokok->deposit_minimal);

			}else{
				for ($i=1; $i <= $input['pemotongan'] ; $i++) {
					$ts_deposit_detail                              = new DepositTransactionDetail();
					$ts_deposit_detail->id                          = $ts_deposit_detail::max('id')+1;
					$ts_deposit_detail->transaction_id              = $ts_deposits->id;
					($i == 1) ? $ts_deposit_detail->deposits_type   = 'pokok': $ts_deposit_detail->deposits_type   = 'sukarela';
					$ts_deposit_detail->debit                       = 0;
					($i == 1) ? $ts_deposit_detail->credit          = $getPokok->deposit_minimal / 2 + $input['sukarela'] + $input['potong']: $ts_deposit_detail->credit  = $getPokok->deposit_minimal / 2 + $input['potong'];
					($i == 1) ? $ts_deposit_detail->total           = $getPokok->deposit_minimal / 2 + $input['sukarela'] + $input['potong']: $ts_deposit_detail->total   = $getPokok->deposit_minimal / 2 + $input['potong'];
					$ts_deposit_detail->save();
				}
				$this->configDeposit($member->id, 'pokok', $getPokok->deposit_minimal/2);


			}

			$this->configDeposit($member->id, 'sukarela', $input['sukarela']);
			$data    =  array(
				'status' => "success",
				'msg'   => 'Akun berhasil dibuat. Silahkan check email anda untuk proses aktivasi.',
			);
			return response()->json($data);
		}
	}

	public function configDeposit($member, $type, $value){
		$configDeposit = new ConfigDepositMembers;
		$configDeposit->member_id = $member;
		$configDeposit->type = $type;
		$configDeposit->value = $value;
		$configDeposit->save();
	}

    public function checkTokeExpired(Request $request)
    {
        try{
            \JWTAuth::parseToken()->authenticate();
            return [
                'status' => 'success',
                'error' => false,
                'message' => 'Token Valid'
            ];
        }catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return [
                'status' => 'failed',
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

}
