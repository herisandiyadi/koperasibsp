<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\cutOff;
use App\Policy;
use App\User;
use App\Member;
use App\Region;
use App\Project;
use App\Deposit;
use App\Position;
use Carbon\Carbon;
use App\DepositTransaction;
use Illuminate\Http\Request;
use App\ConfigDepositMembers;
use App\DepositTransactionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\GlobalController;
use NotificationChannels\OneSignal\OneSignalChannel;

// use Faker\Generator as Faker;


class RegisterController extends LoadController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['region']     = Region::all();
        $data['project']    = Project::all();
		$data['pokok']      = Deposit::where('deposit_name', 'like', '%pokok%')->first();
		$data['position']   = Position::fMemberOnly()->get();
		$data['policy'] = Policy::where('id', 1)->first();

        return view('vendor.adminlte.register', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $global = new GlobalController();

		$input = Input::all();
//    	$getPosition           = Position::where('name', 'like', '%'.$input['position_id'].'%')->first();
		$getPosition = Position::where('name', 'like', '%anggota%')->first();
		$getPokok = Deposit::find(1);
        $getWajib = Deposit::find(2);
        $getSukarela = Deposit::find(3);


        $find = User::where('email', $request->email)->count();
		$findM = Member::where('nik_bsp', $request->nik_bsp)->count();
		// $faker 				   = Faker::create();
        if($find  != null || $findM != null){
            if($find){
                $status = 'email';
                $msg  = 'Maaf, alamat email  yang anda masukkan sudah dipakai sebelumnya.';
            }else{
                $status = 'nik_bsp';
                $msg = 'Maaf, NIK Koperasi  yang anda masukkan sudah dipakai sebelumnya.';
            }
        	$data =  array(
                      'error' => 1,
                      'msg'   => $msg,
                      'status' => $status
                   );
            return response()->json($data);
        }else{


	        $user = new User();
	        $user->id  = $user::max('id')+1;
	        $user->name = $input['fullname'];
	        $user->email = $input['email'];
	        $user->username = $global->getBspNumber();

            // send start email
            $this->sendEmail($user->username, $input['email'], $input['password']);
            // send end   email
	        $user->password    = \Hash::make($input['password']);
	        $user->position_id = $getPosition->id;
	        $user->save();
	        //assign role based on position->level
	        $user->assignRole($getPosition->level->name);

        	// insert into table member
        	$member = new Member();
        	$member->nik_bsp = $input['nik_bsp'];
			$member->nik_koperasi = $user->username;
            $member->first_name = $input['fullname'];
            $member->nik = $input['nik'];
            $member->user_id = $user->id;
            $member->email = $user->email;
            $member->position_id = $user->position_id;
            $member->join_date = now()->format('Y-m-d');

            // $member->region_id                = $input['region_id'];
			// $member->project_id               = $input['project_id'];
			// if(isset($input['branch_id'])){
			// 	$member->branch_id  = $input['branch_id'];
			// }else{
			// 	$member->branch_id  = null;
			// }
            $member->save();
            // insert into table deposit
            //Deposit Wajib
            $depositWajib = new DepositTransaction();
            $depositWajib->member_id = $member->id;
            $depositWajib->ms_deposit_id = $getWajib->id;
            $depositWajib->deposit_number = $global->getDepositNumber();
            $depositWajib->total_deposit = (int) $input['wajib'];
            $depositWajib->deposits_type = 'wajib';
            $depositWajib->type = 'debit';
            $depositWajib->post_date = cutOff::getCutoff();
            $depositWajib->save();

            $depositWajibDetail = new DepositTransactionDetail();
            $depositWajibDetail->transaction_id = $depositWajib->id;
            $depositWajibDetail->deposits_type = 'wajib';
            $depositWajibDetail->debit = (int) $input['wajib'];
            $depositWajibDetail->credit = 0;
            $depositWajibDetail->payment_date = $depositWajib->post_date;
            $depositWajibDetail->total = (int) $input['wajib'];
            $depositWajibDetail->save();
            // End Deposit Wajib

            // Deposit Sukarela
            if($input['sukarela'] != "0"){
                $depositSukarela = new DepositTransaction();
                $depositSukarela->member_id = $member->id;
                $depositSukarela->ms_deposit_id = $getSukarela->id;
                $depositSukarela->deposit_number = $global->getDepositNumber();
                $depositSukarela->total_deposit = (int) $input['sukarela'];
                $depositWajib->deposits_type = 'sukarela';
                $depositSukarela->type = 'debit';
                $depositSukarela->post_date = cutOff::getCutoff();
                $depositSukarela->save();

                $depositSukarelaDetail = new DepositTransactionDetail();
                $depositSukarelaDetail->transaction_id = $depositSukarela->id;
                $depositSukarelaDetail->deposits_type = 'sukarela';
                $depositSukarelaDetail->debit = (int) $input['sukarela'];
                $depositSukarelaDetail->credit = 0;
                $depositSukarelaDetail->payment_date = $depositSukarela->post_date;
                $depositSukarelaDetail->total = (int) $input['sukarela'];
                $depositSukarelaDetail->save();
            }
            // End Deposit Sukarela

        	// Deposit Pokok
            if($input['pemotongan'] == 1){

                $depositPokok = new DepositTransaction();
                $depositPokok->member_id = $member->id;
                $depositPokok->ms_deposit_id = $getPokok->id;
                $depositPokok->deposit_number = $global->getDepositNumber();
                $depositPokok->total_deposit = $getPokok->deposit_minimal;
                $depositWajib->deposits_type = 'pokok';
                $depositPokok->type = 'debit';
                $depositPokok->post_date = cutOff::getCutoff();
                $depositPokok->save();

                $depositPokokDetail = new DepositTransactionDetail();
                $depositPokokDetail->transaction_id = $depositPokok->id;
                $depositPokokDetail->deposits_type = 'pokok';
                $depositPokokDetail->debit = (int) $getPokok->deposit_minimal;
                $depositPokokDetail->credit = 0;
                $depositPokokDetail->payment_date = $depositPokok->post_date;
                $depositPokokDetail->total = (int) $getPokok->deposit_minimal;
                $depositPokokDetail->save();

            }else{
                for ($i=1; $i <= $input['pemotongan'] ; $i++) {
                    $depositPokok = new DepositTransaction();
                    $depositPokok->member_id = $member->id;
                    $depositPokok->ms_deposit_id = $getPokok->id;
                    $depositPokok->type = 'debit';
                    $depositPokok->deposit_number = $global->getDepositNumber();
                    $depositPokok->total_deposit = $getPokok->deposit_minimal / 2;
                    $depositWajib->deposits_type = 'pokok';
                    $depositPokok->post_date = cutOff::getCutoff();
                    $depositPokok->save();

                    $depositPokokDetail = new DepositTransactionDetail();
                    $depositPokokDetail->transaction_id = $depositPokok->id;
                    $depositPokokDetail->deposits_type = 'pokok';
                    $depositPokokDetail->debit = $getPokok->deposit_minimal / 2;
                    $depositPokokDetail->credit = 0;
                    $depositPokokDetail->payment_date = $depositPokok->post_date;
                    $depositPokokDetail->total = $getPokok->deposit_minimal / 2;
                    $depositPokokDetail->save();
				}
			}

            $this->configDeposit($member->id, 'wajib', $input['wajib']);
            $this->configDeposit($member->id, 'pokok', $getPokok->deposit_minimal);
            $this->configDeposit($member->id, 'sukarela', $input['sukarela']);

        	$data    =  array(
						'error' => 0,
						'msg'   => 'Akun berhasil dibuat. Silahkan check email anda untuk proses aktivasi.',
				    	);

            $admins = User::FAdminRegister()->get();
            $member->newMemberBlastTo($admins, ['database', OneSignalChannel::class]);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
