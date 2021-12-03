<?php

namespace App\Http\Controllers\API;

use App\Approvals;
use App\Article;
use App\Bank;
use App\ChangeDeposit;
use App\ConfigDepositMembers;
use App\Contact;
use App\Deposit;
use App\DepositTransactionDetail;
use App\Helpers\ApprovalUser;
use App\Helpers\cutOff;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\LoadController;
use App\Loan;
use App\MemberPlafon;
use App\PencairanSimpanan;
use App\Position;
use App\Region;
use App\Project;
use App\Location;
use App\Policy;
use App\Member;

use App\Helpers\ResponseHelper;
use App\Resign;
use App\TotalDepositMember;
use App\TsDeposits;
use App\TsLoansDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRegistrationRequest;

use Exception;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Validator;
use App\Exceptions\UnauthorizeFeatureException;
use App\Exceptions\UnhandledException;
use NotificationChannels\OneSignal\OneSignalChannel;
use OneSignal;
use App\TsLoans;
use App\DepositTransaction;
use App\User;
use DB;
class ApiController extends GlobalController
{
	//
	public function regions()
	{
		$region = Region::get();
		$data = [
			'status' => 'success',
			'region' => $region
		];
		return $data;
	}

	public function projects()
	{
		$project = Project::get();
		$data = [
			'status' => 'success',
			'project' => $project
		];
		return $data;
	}

	public function locations()
	{
		$location = Location::get();
		$data = [
			'status' => 'success',
			'location' => $location
		];
		return $data;
	}

	public function getPolicy($id)
	{
		$policy = Policy::where('id', $id)->first();
		$data = [
			'status' => 'success',
			'policy' => $policy
		];
		return $data;
	}

	public function register(Request $request){
        $global = new GlobalController();

        $input = Input::all();
//    	$getPosition           = Position::where('name', 'like', '%'.$input['position_id'].'%')->first();
        $getPosition = Position::where('name', 'like', '%anggota%')->first();
        $getPokok = Deposit::find(1);
        $getWajib = Deposit::find(2);
        $getSukarela = Deposit::find(3);

        $find = User::where('email', $request->email)->count();
        $findM = Member::where('nik_bsp', $request->nik_bsp)->count();

        if($find  != null || $findM != null){
            if($find){
                $msg  = 'Maaf, alamat email  yang anda masukkan sudah dipakai sebelumnya.';
            }else{
                $msg = 'Maaf, NIK Koperasi  yang anda masukkan sudah dipakai sebelumnya.';
            }
            $data =  [
                'error' => true,
                'message' => $msg
            ];
            return $data;
        }

        if ($input['wajib'] == "0" || $input['wajib'] == '') {
            $data =  [
                'error' => true,
                'message' => "Simpanan wajib tidak boleh kosong atau 0."
            ];
            return $data;
        }


        $user = new User();
        $user->id = $user::max('id') + 1;
        $user->name = $input['fullname'];
        $user->email = $input['email'];
        $user->username = $global->getBspNumber();

        // send start email
        $LoadController = new LoadController();
        $LoadController->sendEmail($user->username, $input['email'], $input['password']);
        // send end   email
        $user->password = \Hash::make($input['password']);
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
        $member->save();

        // insert into table deposit
        //Deposit Wajib
        $depositWajib = new DepositTransaction();
        $depositWajib->member_id = $member->id;
        $depositWajib->ms_deposit_id = $getWajib->id;
        $depositWajib->deposit_number = $global->getDepositNumber();
        $depositWajib->total_deposit = (int)$input['wajib'];
        $depositWajib->deposits_type = 'wajib';
        $depositWajib->type = 'debit';
        $depositWajib->post_date = cutOff::getCutoff();
        $depositWajib->save();

        $depositWajibDetail = new DepositTransactionDetail();
        $depositWajibDetail->transaction_id = $depositWajib->id;
        $depositWajibDetail->deposits_type = 'wajib';
        $depositWajibDetail->debit = (int)$input['wajib'];
        $depositWajibDetail->credit = 0;
        $depositWajibDetail->payment_date = $depositWajib->post_date;
        $depositWajibDetail->total = (int)$input['wajib'];
        $depositWajibDetail->save();
        // End Deposit Wajib

        // Deposit Sukarela
        if ($input['sukarela'] != "0") {
            $depositSukarela = new DepositTransaction();
            $depositSukarela->member_id = $member->id;
            $depositSukarela->ms_deposit_id = $getSukarela->id;
            $depositSukarela->deposit_number = $global->getDepositNumber();
            $depositSukarela->total_deposit = (int)$input['sukarela'];
            $depositWajib->deposits_type = 'sukarela';
            $depositSukarela->type = 'debit';
            $depositSukarela->post_date = cutOff::getCutoff();
            $depositSukarela->save();

            $depositSukarelaDetail = new DepositTransactionDetail();
            $depositSukarelaDetail->transaction_id = $depositSukarela->id;
            $depositSukarelaDetail->deposits_type = 'sukarela';
            $depositSukarelaDetail->debit = (int)$input['sukarela'];
            $depositSukarelaDetail->credit = 0;
            $depositSukarelaDetail->payment_date = $depositSukarela->post_date;
            $depositSukarelaDetail->total = (int)$input['sukarela'];
            $depositSukarelaDetail->save();
        }
        // End Deposit Sukarela

        // Deposit Pokok
        if ($input['pemotongan'] == 1) {

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
            $depositPokokDetail->debit = (int)$getPokok->deposit_minimal;
            $depositPokokDetail->credit = 0;
            $depositPokokDetail->payment_date = $depositPokok->post_date;
            $depositPokokDetail->total = (int)$getPokok->deposit_minimal;
            $depositPokokDetail->save();

        } else {
            for ($i = 1; $i <= $input['pemotongan']; $i++) {
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

        $data = array(
            'error' => 0,
            'msg' => 'Akun berhasil dibuat. Silahkan check email anda untuk proses aktivasi.',
        );

        $admins = User::FAdminRegister()->get();
        $member->newMemberBlastTo($admins, ['database', OneSignalChannel::class]);

        $data =  [
            'error' => false,
            'message' => "Akun berhasil dibuat. Silahkan check email anda untuk proses aktivasi",
        ];
        return $data;
	}

    public function configDeposit($member, $type, $value){
        $configDeposit = new ConfigDepositMembers;
        $configDeposit->member_id = $member;
        $configDeposit->type = $type;
        $configDeposit->value = $value;
        $configDeposit->save();
    }

	public function crontest()
	{
		\Log::info('via crontab');
	}

	public function postOnesignal()
	{
		$parameters = array(
			'large_icon' => 'https://www.dropbox.com/s/9wevk72p5v5s17b/bsp.png?dl=1',
			'headings' => array(
				'en' => 'halo semuanya ini heading'
			),
			'included_segments' => array('All'),
			'subtitle' => array(
				'en' => 'halo semuanya ini subtitle'
			),
			'contents' => array(
				'en' => 'ini isi dari berita notifikasi'
			),
			'data' => array(
				'user_id' => 2,
				'id' => 1,
				'type' => 'berita'
			),
			'android_accent_color' => 'FFFF0000',
			'big_picture' => 'http://www.bspguard.co.id/wp-content/uploads/2015/09/Slide-2-1.jpg',
			'android_sound' => 'goodmorning'
			// 'android_background_layout' => array(
			// 	'image' => 'https://www.urbanairship.com/images/uploads/blog/push-notification-examples-ios-screenshots.jpg',
			// 	'headings_color' => 'FFFF0000',
			// 	'contents_color' => '000000'
			// )
		);
		$push = OneSignal::sendNotificationCustom($parameters);


		return $push;
	}

	public function getProfile($id){

	   try {
			$getData   = User::leftJoin('ms_members', function($join) {
						$join->on('ms_members.user_id', '=', 'users.id');
						})
						->leftJoin('ms_banks', function($join) {
							$join->on('ms_members.id', '=', 'ms_banks.member_id');
						})
						->leftJoin('positions', function($join) {
							$join->on('ms_members.position_id', '=', 'positions.id');
						})
						->where('users.id', $id)
						->first();

				$data = [
					'status' => 'success',
					'profile' => $getData
				];
				return $data;


	   } catch (\Throwable $th) {
				$data = [
					'status' => 'failed',
					'profile' => ''
				];
				return $data;
	   }
	}

	public function getDataDashboard(Request $request){
		try {
				 $user = auth()->user();
				 $member = $user->member;
                if($member['picture'] != null){
                    $picture = url()->previous().'/images/'.$member->picture;
                }else{
                    $picture = url()->previous().'/images/security-guard.png';
                }
				 $totalDeposit = TsDeposits::getDepositMember($user->member->id);
				 $totalLoan = TsLoans::totalLoans($member["id"]);

				 $ts = TsLoans::leftJoin('ts_loan_details', function($join) {
					$join->on('ts_loans.id', '=', 'ts_loan_details.loan_id');
				})
				->where('ts_loans.member_id', $user->member->id)
                ->where('ts_loan_details.approval', 'belum lunas')
				->get(['ts_loan_details.value']);
				$sum = 0;
				foreach ($ts as $el) {
					$sum += $el->value;
				}

				 $user->member["picture"] = $picture;
				 $user->member["total_deposit"] =  number_format($totalDeposit);
				 $user->member["total_loan"] = number_format($sum);

				 $data = [
					 'status' => 'success',
					 'error' => '',
					 'member' => $user->member
				 ];
				 return $data;

		} catch (\Throwable $th) {
				 $data = [
					 'status' => 'failed',
					 'member' => '',
					 'error' => $th
				 ];
				 return $data;

		}

	 }

    public function getlocation(){
         $LocationDet = Region::with('branch', 'project.locations')->where('id',2)->get();
         return $LocationDet;
    }

    public function getJabatan(){
        $position = Position::fMemberOnly()->get();
        $data = [
            'status' => 'success',
            'jabatan' => $position
        ];
        return $data;
    }

    public function getDeposit(){
        try {
            $deposit = Deposit::get();
            $data = [
                'status' => 'success',
                'data' => $deposit,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function getLoan(){
        try {
            $data = Loan::Publish()->get();
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function myLoan(){
        try {
            $data = TsLoans::with('ms_loans:id,loan_name,logo')->where('member_id', auth()->user()->member->id)->get();
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function myDetailLoan($id){
        try {
            $data = TsLoans::with('detail','ms_loans:id,loan_name,logo')->find($id);
			$data['sisa_pinjaman'] = DB::table('ts_loan_details')->where('loan_id', $data->id)->where('approval', 'belum lunas')->sum('value');
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function news(){
        try {
            $data = Article::get();
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function sliderNews(){
        try {
            $data = Article::limit(5)->orderBy('id', 'DESC')->get();
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function filterLoan(Request $request){
        try {
            $member_id = auth()->user()->member->id;
            $r = in_array('semua', $request->input('filter'), true);
            $data = TsLoans::with('ms_loans:id,loan_name,logo')->where('member_id', $member_id)->get();
            if($request->input('filter') == []){
                $data = TsLoans::with('ms_loans:id,loan_name,logo')->where('member_id', $member_id)->get();
            }
            if(!$r && $request->input('filter') != []){
                $data = TsLoans::with('ms_loans:id,loan_name,logo')->whereIn('approval', $request->input('filter'))->where('member_id', $member_id)->get();
            }
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function myDeposit(){
        try {
            $simpananMember = TotalDepositMember::with('ms_deposit:id,deposit_name')->where('member_id', auth()->user()->member->id)->get();

			$totalWajib = TsDeposits::totalDepositWajib(auth()->user()->member->id);
            $totalPokok = TsDeposits::totalDepositPokok(auth()->user()->member->id);
            $totalSukarela = TsDeposits::totalDepositSukarela(auth()->user()->member->id);
            $totalShu = TsDeposits::totalDepositShu(auth()->user()->member->id);

            $data = [
				[
					"id" => 1,
					"deposit_name" => "Simpanan Pokok",
					"value" =>  $totalPokok
				],
				[
					"id" => 2,
					"deposit_name" => "Simpanan Wajib",
					"value" =>  $totalWajib
				],
				[
					"id" => 3,
					"deposit_name" => "Simpanan Sukarela",
					"value" =>  $totalSukarela
				],
				[
					"id" => 5,
					"deposit_name" => "Simpanan SHU Ditahan",
					"value" =>  $totalShu
				]
			];
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function myDetailDeposit($id){
        try {
            $data = TsDeposits::with('detail','ms_deposit:id,deposit_name')
                ->where('member_id', auth()->user()->member->id)
                ->where('ms_deposit_id', $id)
                ->get();
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function filterDeposit(Request $request, $id){
        try {
            $member_id = auth()->user()->member->id;
            $r = in_array('semua', $request->input('filter'), true);
            $data = TsDeposits::with('detail','ms_deposit:id,deposit_name')
                ->where('member_id', auth()->user()->member->id)
                ->where('ms_deposit_id', $id);
            if($request->input('filter') == []){
                $data = $data->get();
            }
            if(!$r && $request->input('filter') != []){
                $terbaru = in_array('terbaru', $request->input('filter'), true);
                $terlama = in_array('terlama', $request->input('filter'), true);
                $paid = in_array('paid', $request->input('filter'), true);
                $unpaid = in_array('unpaid', $request->input('filter'), true);
                $debit = in_array('debit', $request->input('filter'), true);
                $credit = in_array('credit', $request->input('filter'), true);

                if($terbaru){
                    $data = $data->orderBy('post_date', 'DESC');
                }
                if($terlama){
                    $data = $data->orderBy('post_date', 'ASC');
                }
                if($paid){
                    $data = $data->where('status', 'paid');
                }
                if($unpaid){
                    $data = $data->where('status', 'unpaid');
                }
                if($debit){
                    $data = $data->where('type', 'debit');
                }
                if($credit){
                    $data = $data->orderBy('type', 'credit');
                }

                $data = $data->get();
            }
            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function myProfile(){
        try {

            $member = Member::with('position:id,name', 'project:id,project_name', 'region:id,name_area')->where('id', auth()->user()->member->id)->first();

            if($member->picture != null){
                $member['picture'] = url()->previous().'/images/'.$member->picture;
            }else{
                $member['picture'] = url()->previous().'/images/security-guard.png';
            }
            $data = [
                'status' => 'success',
                'data' => $member,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function postResign(Request $request){
        try {
            $spcMember = Member::where('user_id', auth()->user()->id)->first();
            $checkRsn  = $this->checkRsn($spcMember->id);
            if ($checkRsn) {
                $data = [
                    'status' => 'failed',
                    'message' => 'Anda telah melakukan pengajuan pengunduran diri. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
                    'error' => true
                ];
                return $data;
            }
            // cek simpanan cukup untuk menutup hutang
            $close = $this->close($spcMember->id);
            if ($close) {
                $data = [
                    'status' => 'failed',
                    'message' => 'Pengunduran diri tidak bisa dilakukan. Karena, simpanan anda tidak cukup untuk menutup pinjaman yang belum lunas.',
                    'error' => true
                ];
                return $data;
            }
            // jika validasi terlewati
            $newRsn = new Resign();
            $newRsn->member_id = $spcMember->id;
            $newRsn->date = $request['date'];
            $newRsn->reason = $request['reason'];
            $newRsn->approval = 'waiting';
            $newRsn->save();

            $approvals = User::FUserApproval()->get();
            $newRsn->newResignBlastTo($approvals, ['database', OneSignalChannel::class]);

            $data = [
                'status' => 'success',
                'data' => $newRsn,
                'message' => 'Pengunduran diri berhasil diajukan',
                'error' => false
            ];

            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function postRetrieveDeposit(Request $request){
        $member = auth()->user()->member;
        $sukarela = TsDeposits::totalDepositSukarela($member->id);
        if($request->jumlah > $sukarela){
            $data = [
                'status' => 'failed',
                'message' => 'Dana yang diajukan lebih besar dari jumlah simpanan.',
                'error' => true
            ];
            return $data;
        }
        $bank =  $member->bank[0];

        if(empty($bank)){
            $data = [
                'status' => 'failed',
                'message' => 'Anda belum memiliki data bank, silahkan tambahkan informasi bank anda.',
                'error' => true
            ];
            return $data;
        }

        $pencairan = new PencairanSimpanan();
        $pencairan->member_id = $member->id;
        $pencairan->bank_id = $bank->id;
        $pencairan->jumlah = $request->jumlah;
        $pencairan->date = $request->date;
        $pencairan->phone = $member->phone_number;
        $pencairan->save();

        $data = [
            'status' => 'success',
            'data' => $pencairan,
            'message' => 'Pengajuan pencairan berhasil',
            'error' => false
        ];

        return $data;
    }

    public function policy($id){

	    $policy = Policy::find($id);
        $data = [
            'status' => 'success',
            'data' => $policy,
            'error' => false
        ];

        return $data;

    }

    public function myBank(){
        try {
            $bank = auth()->user()->member->bank->first();
            $data = [
                'status' => 'success',
                'data' => $bank,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function updateMyBank(Request $request){
        try {
            $bank = auth()->user()->member->bank->first();
            $bank->bank_name = $request->bank_name;
            $bank->bank_account_name = $request->bank_account_name;
            $bank->bank_account_number = $request->bank_account_number;
            $bank->bank_branch = $request->bank_branch;
            $bank->save();
            $data = [
                'status' => 'success',
                'data' => $bank,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function postLoan(Request $request)
    {
        try {

            $userSubmision = auth()->user();
            $penjamin = User::where('id', $request->penjamin)->get();
            $adminApproval = User::AdminApproval()->get();
            $approvals = ApprovalUser::getApproval($userSubmision);
            $approvemans = collect($penjamin)->merge($approvals)->merge($adminApproval)->merge([$userSubmision]);
//            $penjamin = ApprovalUser::getPenjamin($userSubmision);

            $userSubmision = auth()->user();
            $memberSubmision = $userSubmision->member;
            $start = now()->format('Y-m-d');
            $end = now()->addMonth($request->tenor);
            $value = $request->value;

//            $checkPlafon  = MemberPlafon::where('member_id',$memberSubmision->id)->first();
//            $checkLoan    = TsLoans::where([
//                ['member_id', $memberSubmision->id],
//                ['approval', 'belum lunas']
//            ])->first();
//
//            $checkApply   = TsLoans::where('member_id', $memberSubmision->id)
//                ->where('approval', 'menunggu')
//                ->first();
//
//            $checkTsDep   = DepositTransaction::where(['member_id' => $memberSubmision->id, 'status' => 'paid']);
//            if($checkTsDep->count() < 2) {
//                $data = [
//                    'status' => 'failed',
//                    'message' => 'Deposit keanggotaan anda masih kurang dari 2 bulan atau belum lunas. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                    'error' => true
//                ];
//                return $data;
//            }
//
//            if(!$userSubmision->member->isActive()){
//                $data = [
//                    'status' => 'failed',
//                    'message' => 'Keanggotaan anda belum aktif. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                    'error' => true
//                ];
//                return $data;
//            }
//
//            if(!$checkPlafon) {
//                $data = [
//                    'status' => 'failed',
//                    'message' => 'Batas nominal cicilan anda belum tersedia. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                    'error' => true
//                ];
//                return $data;
//            }else {
//                if($value > $checkPlafon->nominal){
//                    $data = [
//                        'status' => 'failed',
//                        'message' => 'Batas nominal cicilan yang anda masukkan melebihi batas. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                        'error' => true
//                    ];
//                    return $data;
//                } else {
//                    if($checkLoan) {
//                        $data = [
//                            'status' => 'failed',
//                            'message' => 'Anda masih memiliki pinjaman yang belum lunas.',
//                            'error' => true
//                        ];
//                        return $data;
//                    } elseif ($checkApply) {
//                        $data = [
//                            'status' => 'failed',
//                            'message' => 'Anda telah melakukan pengajuan pinjaman sebelumnya. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                            'error' => true
//                        ];
//                        return $data;
//                    }
//                }
//            }

            $msLoans = Loan::find($request->loan_id);

            $from = Carbon::parse($start);
            $to = Carbon::parse($end);
            $diff_in_months = $to->diffInMonths($from);


            $cutoff = cutOff::getCutoff();
            $gte_loan = $to->gte($cutoff);

            if(!$gte_loan){
                $startDate = $start;
            }else{
                $startDate = $cutoff;
            }

            $loan_value = $value / $diff_in_months;
            $loan_value = ceil($loan_value);
            $biayaJasa = ceil($value * ($msLoans->rate_of_interest/100));
            $biayaProvisi = ceil($value * ($msLoans->provisi / 100));
            $biayaBungaBerjalan = cutOff::getBungaBerjalan($loan_value, $msLoans->biaya_bunga_berjalan, now()->format('Y-m-d'));

            $loanNumber = new GlobalController();
            $loan = new TsLoans();
            $loan->loan_number = $loanNumber->getLoanNumber();
            $loan->member_id = $memberSubmision->id;
            $loan->start_date = Carbon::parse($startDate)->format('Y-m-25');
            $loan->end_date = Carbon::parse($end)->format('Y-m-25');
            $loan->loan_id = $msLoans->id;
            $loan->value = $value;
            $loan->biaya_jasa = $biayaJasa;
            $loan->biaya_admin = $msLoans->biaya_admin;
            $loan->biaya_provisi = $biayaProvisi;
            $loan->biaya_bunga_berjalan = $biayaBungaBerjalan;
            $loan->approval = 'menunggu';
            $loan->period = $diff_in_months;
            $loan->in_period = 0;
            $loan->rate_of_interest = $msLoans->rate_of_interest;
            $loan->save();
            $loan->generateApprovalsLoan($approvemans);
            $b1 = 1;
            for ($a1 = 0; $a1 < $diff_in_months; $a1++) {

                $paydated = Carbon::parse($startDate)->addMonth($a1);
                $val = $loan_value / $diff_in_months;
                $service = $val * ($msLoans->rate_of_interest / 100);

                $loan_detail = new TsLoansDetail();
                $loan_detail->loan_id = $loan->id;
                $loan_detail->loan_number = $loan->loan_number;
                $loan_detail->value = $loan_value;
                $loan_detail->service = $service;
                $loan_detail->pay_date = $paydated;
                $loan_detail->in_period = $b1 + $a1;
                $loan_detail->approval = $loan->approval;
                $loan_detail->save();

            }

            $data = [
                'status' => 'success',
                'data' => $loan,
                'error' => false
            ];
            return $data;

        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function getLoanApproval()
    {
        try {
            $approvalID = auth()->user()->id;
            $approvalLoan = Approvals::with('ts_loans')
                ->whereJsonContains('approval', ['id' => $approvalID, 'status' => 'menunggu'])->get();

            $data = [
                'status' => 'success',
                'data' => $approvalLoan,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function approveLoan(Request $request){
        try {
            $approvalID = auth()->user()->id;
            $approvalLoan = Approvals::with('ts_loans.ms_loans','ts_loans.detail')->where('fk', $request->loan_id)->whereJsonContains('approval', ['id' => $approvalID, 'status' => 'menunggu'])->first();
            $loans = $approvalLoan->ts_loans;
            $detailLoans = $loans->detail;
            $approveman = collect($approvalLoan->approval);


            if($request->approve_status == 'direvisi'){
                $value = $request->revision_value;
                $loan_value = $value / $loans->period;
                $loan_value = ceil($loan_value);
                $service = ceil(($loan_value/100) * $loans->rate_of_interest);
                $loans->value = $value;
                foreach ($detailLoans as $detail){
                    $detail->value = $loan_value;
                    $detail->service = $service;
//                    $detail->save();
                }
//                $loans->save();
                $approvalLoan->is_revision = true;
            }

            if($request->approve_status == 'disetujui'){
                if($approvalLoan->user->isMember()){
                    $loans->approval = $request->approve_status;
                    foreach ($detailLoans as $detail){
                        $detail->approval = $request->approve_status;
//                    $detail->save();
                    }
//                $loans->save();
                }
            }

            if($request->approve_status == 'ditolak'){
                $loans->approval = $request->approve_status;
                foreach ($detailLoans as $detail){
                    $detail->approval = $request->approve_status;
//                    $detail->save();
                }
//                $loans->save();
                $approvalLoan->is_revision = true;
            }

            return $loans;
            $data = [
                'status' => 'success',
                'data' => $loans,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function getTopLoan(){
        try {
            $data = TsLoans::getTopPinjamanArea([])->get();

            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function notifications(Request $request){
        try {
            $perpage =  $request->input('limit', 15);
            $page =  $request->input('page', 1);

            $user = auth()->user();
            $data =  DatabaseNotification::where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('read_at', 'asc')
                ->paginate($perpage, ['*'], 'page', $page);

            return $data;

            $data = [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function markAsReadNotification($id){

	    try{
            $notifications = auth()->user()->notifications()
                ->whereNull('read_at')
                ->where('id', $id);
            if($notifications->count() > 0)
            {
                $notifications->update(['read_at' => now()->toDateTimeString()]);
            }

            $data = [
                'status' => 'success',
                'data' => $notifications,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function markAllAsReadNotification(){

        try{
            $notifications = auth()->user()->notifications()
                ->whereNull('read_at');
            if($notifications->count() > 0)
            {
                $notifications->update(['read_at' => now()->toDateTimeString()]);
            }

            $data = [
                'status' => 'success',
                'data' => $notifications,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }

    }

    public function countNotification(){
        try{
            $notifications = auth()->user()->notifications()
                ->whereNull('read_at')->count();
            $data = [
                'status' => 'success',
                'data' => [
                    'totalUnread' => $notifications
                ],
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function deleteNotification($id){
        try{
            $notifications = auth()->user()->notifications()
                ->whereNull('read_at')
                ->where('id', $id);
            if($notifications->count() > 0)
            {
                $notifications->delete();
            }

            $data = [
                'status' => 'success',
                'data' => $notifications,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function penjaminLoan(){
        try{
            $penjamin = ApprovalUser::getPenjamin(auth()->user());
            $data = [
                'status' => 'success',
                'data' => $penjamin,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function getBungaBerjalan(){
        try{
            $dayBungaBerjalan = cutOff::getDayBungaBerjalan(now()->format('Y-m-d'));
            $data = [
                'status' => 'success',
                'data' => $dayBungaBerjalan,
                'error' => false
            ];
            return $data;
        } catch (\Throwable $th) {
            $data = [
                'status' => 'failed',
                'error' => $th->getMessage()
            ];
            return $data;
        }
    }

    public function postContact(Request $request){
        try {
            $user = auth()->user();
            $admins = User::FAdminRegister()->get();
            foreach ($admins as $admin)
            {

                $contact = [
                    'from_id' => $user->id,
                    'to_id' => $admin->id,
                    'judul' => $request->judul,
                    'pesan' => $request->pesan
                ];

                $contact = Contact::create($contact);
                $contact['email'] = $user->email;
                $user->newContactNotification($admin, $contact);

            }

            return [
                'status' => 'success',
                'message' => 'Terima kasih, pesan anda telah terkirim',
                'error' => false
            ];

        } catch (\Throwable $th) {

            return [
                'status' => 'failed',
                'error' => true,
                'message' => $th->getMessage()
            ];

        }
    }

    public function updateFotoProfile(Request $request)
    {
        $input = Input::all();
        $user = auth()->user();
        $member = Member::findOrFail($user->member->id);
        if(isset($input['change_image'])) {
            $name = str_replace(' ', '',$member->first_name);
            $base64_string = 'data:image/png;base64,'.$input['change_image'];
            $imageInfo = explode(";base64,", $base64_string);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);
            $image = str_replace(' ', '+', $imageInfo[1]);
            $imageName = 'profile_'.$name.'.'.$imgExt;
            \Storage::disk('public')->put($imageName, base64_decode($image));
            $member->picture  = $imageName;
            $member->save();
        }

        return [
            'status' => 'success',
            'message' => 'Foto Profile berhasil diubah',
            'error' => false
        ];
    }

    public function approvalLoan()
    {
        try {
            $user = auth()->user();
            $selected = Approvals::where('user_id', $user->id)->with('ts_loans.member', 'ts_loans.ms_loans')->where('status', 'menunggu')->get();

            return [
                'status' => 'success',
                'data' => $selected,
                'error' => false
            ];

        } catch (\Throwable $th) {

            return [
                'status' => 'failed',
                'error' => true,
                'message' => $th->getMessage()
            ];

        }
    }

    public function rincianSimpanan()
    {
        try {
			$totalWajib = TsDeposits::totalDepositWajib(auth()->user()->member->id);
            $totalPokok = TsDeposits::totalDepositPokok(auth()->user()->member->id);
            $totalSukarela = TsDeposits::totalDepositSukarela(auth()->user()->member->id);
            $totalShu = TsDeposits::totalDepositShu(auth()->user()->member->id);
			$data['wajib'] = $totalWajib;
			$data['sukarela'] = $totalSukarela;
            return [
                'status' => 'success',
                'data' => $data,
                'error' => false
            ];

        } catch (\Throwable $th) {

            return [
                'status' => 'failed',
                'error' => true,
                'message' => $th->getMessage()
            ];

        }
    }

    public function ajukanPerubahanSimpanan(Request $request)
    {
        try {
            $member = auth()->user()->member;

            if($request->wajib == 0 || $request->wajib == ''){
                return [
                    'status' => 'failed',
                    'error' => true,
                    'message' => 'dana yang diubah tidak boleh nol 0'
                ];
            }

            $perubahan = new ChangeDeposit();
            $perubahan->member_id = $member->id;
            $perubahan->date = now()->format('Y-m-d');
            $perubahan->last_wajib = $request->last_wajib;
            $perubahan->last_sukarela = $request->last_sukarela;
            $perubahan->new_wajib = $request->wajib;
            $perubahan->new_sukarela = $request->sukarela;
            $perubahan->approval = false;
            $perubahan->save();

            $users = User::where('id', 1)->whereNotNull('os_token')->get();
            $perubahan->blastTo($users,['mail',OneSignalChannel::class]);

            return [
                'status' => 'success',
                'data' => $perubahan,
                'error' => false
            ];

        } catch (\Throwable $th) {

            return [
            'status' => 'failed',
            'error' => true,
            'message' => $th->getMessage()
            ];

        }
    }

	public function updatePassword(Request $request){
        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required_with:new_password|same:new_password|min:6',
        ]);

        $hashedPassword = auth()->user()->password;

        if (\Hash::check($request->old_password , $hashedPassword )) {

            if (!\Hash::check($request->new_password , $hashedPassword)) {

                $users = User::find(auth()->user()->id);
                $users->password = bcrypt($request->new_password);
                User::where( 'id' , auth()->user()->id)->update( array( 'password' =>  $users->password));
                return [
					'status' => 'success',
					'data' => $user,
					'error' => false,
					'message' => 'Password Berhasil diubah, silahkan login kembali dengan password baru anda.'
				];
            } else{
				return [
					'status' => 'failed',
					'data' => '',
					'error' => true,
					'message' => 'Password Baru Tidak Boleh Sama dengan Password Lama.'
				];
            }

        } else {
			return [
				'status' => 'failed',
				'data' => '',
				'error' => true,
				'message' => 'Password Lama yang Anda Masukan Salah.'
			];

        }

    }

	public function updateEmail(Request $request){
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $hashedPassword = auth()->user()->password;
        if (\Hash::check($request->password , $hashedPassword )) {

            if (!\Hash::check($request->email , auth()->user()->email)) {

                $users = User::find(auth()->user()->id);
                $users->email = $request->email;
                User::where( 'id' , auth()->user()->id)->update( array( 'email' =>  $users->email));
                return [
					'status' => 'success',
					'data' => $user,
					'error' => false,
					'message' => 'Email Berhasil diubah, silahkan login kembali dengan Email baru anda.'
				];
            } else{
                return [
					'status' => 'failed',
					'data' => '',
					'error' => true,
					'message' => 'Email Baru Tidak Boleh Sama dengan Email Lama.'
				];
            }

        } else {
			return [
				'status' => 'failed',
				'data' => '',
				'error' => true,
				'message' => 'Email Lama yang Anda Masukan Salah.'
			];
        }

    }
}
