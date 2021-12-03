<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\ApprovalUser;
use App\Helpers\cutOff;
use App\Notifications\LoanApplicationStatusUpdated;
use App\TsLoansDetail;
use DB;
use Auth;
use App\Bank;
use App\Loan;
use App\User;
use App\Member;
use App\TsLoans;
use App\Position;
use Carbon\Carbon;
use App\MemberPlafon;
use App\DepositTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\LoadController;
use App\Http\Controllers\GlobalController;
use NotificationChannels\OneSignal\OneSignalChannel;
use function foo\func;

class PanelController extends LoadController
{
    function __construct()
    {
        $this->globalFunc        = new GlobalController();
    }

    public function profile()
    {
        if(!auth()->user()->isMember()){
            return abort(403);
        }
    	$bank_member = Bank::where('member_id', Auth::user()->member->id)->first();
      $spcMember   = Member::where('email', Auth::user()->member->email)->first();
    	return view('dashboards.profile', compact('bank_member', 'spcMember'));
    }
    public function updateStaff(Request $request)
    {
      $input             = Input::all();
      $getID             = Member::whereEmail($input['email'])->first();
      $getID->region_id  = $input['region_id'];
      $getID->branch_id  = $input['branch_id'];
      $getID->project_id = $input['project_id'];
      $getID->save();
      $data              =  array(
                             'error' => 0,
                             'msg'   => 'Pembaharuan data pribadi berhasil.',
                             );
      return response()->json($data);

    }
//    public function updateProfile(Request $request)
//    {
//        $input                   = Input::all();
//        if(isset($input['update_data'])){
//        $getID                   = Member::whereEmail($input['update_data'])->first();
//        $getID                   = $getID->id;
//        } else {
//        $getID                   = Auth::User()->member->id;
//        }
//        // get keys with value not null
//        $getMember               = Member::findOrFail($getID);
//        if(isset($input['change_image'])) {
//            $file                = $request->file('attach');
//            $fileName            = $file->getClientOriginalName();
//            $request->file('attach')->move('images/', $fileName);
//            $getMember->picture  = $fileName;
//            $getMember->save();
//            $data                =  array(
//                                     'error' => 0,
//                                     'msg'   => 'Pembaharuan foto profile berhasil.',
//                                     );
//            return response()->json($data);
//        }
//        $getKey                  = $this->globalFunc->getKeysArr($input);
//        if(isset($input['first_name'])){
//          $first_name            = $input['first_name'];
//          $last_name             = $input['last_name'];
//          // update user data
//          if(isset($input['update_data'])){
//          $getUser               = User::whereEmail($input['update_data'])->first();
//          } else {
//          $getUser               = User::find(Auth::User()->id);
//
//          }
//          $getUser->name         = $first_name.' '. $last_name;
//          if(isset($input['position_id'])){
//          $getUser->position_id  = $input['position_id'];
//          }
//          $getUser->save();
//        }
//        // update member data
//        foreach ($getKey as $key) {
//          if(Schema::hasColumn($this->globalFunc->tableName(new Member()), $key)) {
//            if($key == 'is_active' && $input[$key] == 'zero') {
//                // check change status loan
//                $cekLoan      = TsLoans::where(['member_id' => $getID, 'approval' => 'disetujui'])->where('status', '!=', 'lunas')->first();
//                if($cekLoan){
//                  $data       =  array(
//                                'error' => 1,
//                                'msg'   => 'Maaf member masih terdata dalam tagihan pinjaman belum lunas. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
//                                  );
//                                return response()->json($data);
//                } else{
//                  $input[$key] = 0;
//                }
//            } elseif($key == 'is_active' && $input[$key] != 'zero'){
//              // send email for activated member by admin
//              $statBe4     = $getUser->member->is_active;
//              if (!$statBe4) {
//              $this->sendEmailActiv($getUser->name, $getUser->email, 'activated');
//              }
//            }
//            if ($key == 'position_id') {
//            }
//            $getMember->$key              = $input[$key];
//          }
//        }
//        $getMember->phone_number          = $input['phone_number'];
//        $getMember->save();
//        // update bank data
//        $getBank                          = Bank::where('member_id', $getID)->first();
//        $insertBank                       = new Bank();
//        if(!$getBank) {
//         $insertBank->id                  = $insertBank::max('id')+1;
//         $insertBank->member_id           = $getID;
//          foreach ($getKey as $key) {
//           if(Schema::hasColumn($this->globalFunc->tableName($insertBank), $key)) {
//             $insertBank->$key            = $input[$key];
//           }
//          }
//          $insertBank->save();
//        } else {
//            foreach ($getKey as $key) {
//              if(Schema::hasColumn($this->globalFunc->tableName($insertBank), $key)) {
//                $getBank->$key            = $input[$key];
//              }
//            }
//            $getBank->save();
//        }
//    	$data         =  array(
//						'error' => 0,
//						'msg'   => 'Pembaharuan data pribadi berhasil.',
//				    	);
//      return response()->json($data);
//    }


    public function updateProfile(Request $request)
    {
        $input                   = Input::all();
        if(isset($input['update_data'])){
            $getID                   = Member::whereEmail($input['update_data'])->first();
            $getID                   = $getID->id;
        } else {
            $getID                   = Auth::User()->member->id;
        }
        // get keys with value not null
        $getMember               = Member::findOrFail($getID);
        if(isset($input['change_image'])) {
            $file                = $request->file('attach');
            $fileName            = $file->getClientOriginalName();
            $request->file('attach')->move('images/', $fileName);
            $getMember->picture  = $fileName;
            $getMember->save();
            $data                =  array(
                'error' => 0,
                'msg'   => 'Pembaharuan foto profile berhasil.',
            );
            return response()->json($data);
        }

        if(isset($input['first_name'])){
            $first_name            = $input['first_name'];
            $last_name             = $input['last_name'];
            // update user data
            if(isset($input['update_data'])){
                $getUser               = User::whereEmail($input['update_data'])->first();
            } else {
                $getUser               = User::find(Auth::User()->id);

            }
            $getUser->name         = $first_name.' '. $last_name;
            if(isset($input['position_id'])){
                $getUser->position_id  = $input['position_id'];
            }
            $getUser->save();
        }

        $getMember->phone_number          = $input['phone_number'];
        $getMember->save();
        // update bank data
        $getBank                          = Bank::where('member_id', $getID)->first();
        if($getBank) {
            $getBank->bank_name = $input['bank_name'];
            $getBank->bank_account_name = $input['bank_account_name'];
            $getBank->bank_account_number = $input['bank_account_number'];
            $getBank->bank_branch = $input['bank_branch'];
            $getBank->save();
        } else {
            $newBank  = new Bank();
            $newBank->member_id = $getID;
            $newBank->bank_name = $input['bank_name'];
            $newBank->bank_account_name = $input['bank_account_name'];
            $newBank->bank_account_number = $input['bank_account_number'];
            $newBank->bank_branch = $input['bank_branch'];
            $newBank->save();
        }
        $getMember->first_name = $input['first_name'];
        $getMember->is_active = $input['is_active'];
        $getMember->address = $input['address'];
        $getMember->religion = $input['religion'];
        $getMember->gender = $input['gender'];
        $getMember->dob = $input['dob'];
        $getMember->position_id = $input['position_id'];

        $getMember->save();

        $data         =  array(
            'error' => 0,
            'msg'   => 'Pembaharuan data pribadi berhasil.',
        );
        return response()->json($data);
    }

    public function loanAggrement()
    {
      $getLoans     = Loan::Publish()->get();
      return view('dashboards.loan-aggrement', compact('getLoans'));
    }
    public function pickAggrement($el='')
    {
		// dd(auth()->user()->roles);
        $decypt_el    = \Crypt::decrypt($el);
        $findLoan     = Loan::findOrFail($decypt_el);
        $penjamin = ApprovalUser::getPenjamin(auth()->user());

        $tenors = [];
        $tenor = 0;
      for ($a=0; $a<$findLoan['tenor']; $a++){
          $tenor += 1;
          array_push($tenors, $tenor);
      }
      $getMember    = Member::findOrFail(Auth::user()->member->id);
      $dayBungaBerjalan = cutOff::getDayBungaBerjalan(now()->format('Y-m-d'));
        return view('dashboards.loan-specific', compact('findLoan', 'getMember', 'tenors', 'dayBungaBerjalan', 'penjamin'));
    }
    public function saveLoan(Request $request)
    {
      $input        = Input::all();
      $period       = $input['period'];
      $value        = $input['value'];
      $toDay        = Carbon::now()->format('Y-m-d');
      $loan_id      = $this->globalFunc->decrypter($input['loan_id']);
      $member_id    = $this->globalFunc->decrypter($input['member_id']);
      // checking tsloan
      $checkLoan    = TsLoans::where([
                        ['member_id', $member_id],
                        ['approval', 'belum lunas'],
                        ['approval', 'disetujui']
                      ])->first();
      $checkApply   = TsLoans::where('member_id', $member_id)
                      ->where('approval', 'menunggu')
                      ->first();
      $checkActive  = Member::where([
                      'id' => Auth::user()->member->id,
                      'is_active' => 1
                       ]);
      if(!$checkActive) {
        $data       =  array(
            'error' => 1,
            'msg'   => 'Keanggotaan anda belum aktif. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
              );
            return response()->json($data);
      }
      $checkTsDep   = DepositTransaction::where(['member_id' => Auth::user()->member->id, 'status' => 'paid']);
      if($checkTsDep->count() < 2) {
        $data       =  array(
            'error' => 1,
            'msg'   => 'Deposit keanggotaan anda masih kurang dari 2 bulan atau belum lunas. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
              );
            return response()->json($data);
      }
      $checkPlafon  = MemberPlafon::where('member_id', Auth::user()->member->id)->first();
      if(!$checkPlafon) {
        $data       =  array(
            'error' => 1,
            'msg'   => 'Batas nominal cicilan anda belum tersedia. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
              );
            return response()->json($data);
      }else {
        $nominal    = $checkPlafon->nominal;
        if($value > $nominal){
          $data       =  array(
            'error' => 1,
            'msg'   => 'Batas nominal cicilan yang anda masukkan melebihi batas. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
              );
          return response()->json($data);
        } else {
          if($checkLoan) {
            $data       =  array(
                'error' => 1,
                'msg'   => 'Anda masih memiliki pinjaman yang belum lunas.',
                  );
            return response()->json($data);
          } elseif ($checkApply) {
            $data       =  array(
                'error' => 1,
                'msg'   => 'Anda telah melakukan pengajuan pinjaman sebelumnya. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
                  );
            return response()->json($data);
           }
        }
      }

    $findLoan = Loan::findOrFail($loan_id);
    if($findLoan->attachment && !$request->hasfile('images')){
        $data       =  array(
            'error' => 1,
            'msg'   => 'Lampiran pinjaman wajib diisi',
        );
        return response()->json($data);
    }

    $name = '';
    if ($request->hasfile('images')) {
        $file = $request->file('images');
        $name = time() . $file->getClientOriginalName();
        $file->move(public_path() . '/images/pinjaman/', $name);
    }
    $penjamin = User::where('id', $request->penjamin)->get();
    $adminApproval = User::AdminApproval()->get();
    $approvals = ApprovalUser::getApproval(auth()->user());
    $approvemans = collect($penjamin)->merge($approvals)->merge($adminApproval)->merge([auth()->user()]);
    $payDate = cutOff::getCutoff();
    $loanNumber = new GlobalController();
      // getting master data loan
      $biayaProvisi = $value * ($findLoan->provisi / 100);
      $biayaJasa = ceil($value * ($findLoan->rate_of_interest/100));

      $biayaBungaBerjalan = cutOff::getBungaBerjalan($value, $findLoan->biaya_bunga_berjalan, now()->format('Y-m-d'));
      // insert tsloan
      $tsLoan                   = new TsLoans();
      $tsLoan->id               = $tsLoan::max('id')+1;
      $tsLoan->member_id        = $member_id;
      $tsLoan->loan_number      = $loanNumber->getLoanNumber();
      $tsLoan->loan_id          = $loan_id;
      $tsLoan->value            = $value;
      $tsLoan->period           = $period;
      $tsLoan->start_date       = $payDate;
      $tsLoan->biaya_admin      = $findLoan->biaya_admin;
      $tsLoan->biaya_jasa       = $biayaJasa;
      $tsLoan->biaya_transfer   = $findLoan->biaya_transfer;
      $tsLoan->biaya_provisi    = $biayaProvisi;
      $tsLoan->biaya_bunga_berjalan = $biayaBungaBerjalan;
      $tsLoan->end_date         = Carbon::parse($payDate)->addMonth($period);
      $tsLoan->in_period        = 0;
      $tsLoan->rate_of_interest = $findLoan->rate_of_interest;
      $tsLoan->plafon           = $findLoan->plafon;
      $tsLoan->attachment       = $name;
      $tsLoan->approval         = 'menunggu';
      $tsLoan->save();
      $tsLoan->generateApprovalsLoan($approvemans);


        $b1 = 1;
        for ($a1 = 0; $a1 < $period; $a1++) {
          $val = $value / $period;
          $service = $value * ($findLoan->rate_of_interest / 100);

          $loan_detail = new TsLoansDetail();
          $loan_detail->loan_id = $tsLoan->id;
          $loan_detail->loan_number = $tsLoan->loan_number;
          $loan_detail->value = $val;
          $loan_detail->service = $service;
          $loan_detail->pay_date = Carbon::parse($payDate)->addMonth($a1);
          $loan_detail->in_period = $b1 + $a1;
          $loan_detail->approval = 'menunggu';
          $loan_detail->save();
      }

        $approvals = User::FUserApproval()->get();
        $tsLoan->newLoanBlastTo($approvals, ['database', OneSignalChannel::class]);
      $data         =  array(
            'error' => 0,
            'msg'   => 'Pengajuan pinjaman berhasil. Silahkan menunggu informasi persetujuan lebih lanjut.',
              );
            return response()->json($data);
    }

    public function updateProfilePhoto(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'file' => 'mimes:jpg,jpeg,png,bmp,tiff |max:4096',
            ],
            $messages = [
                'required' => 'The :attribute field is required.',
                'mimes' => 'Only jpg, jpeg, png, bmp,tiff are allowed.'
            ]
        );

        if ($validator->fails()) {
            $fieldsWithErrorMessagesArray = $validator->messages()->get('*');
            $data                =  array(
                'error' => 1,
                'msg'   => $fieldsWithErrorMessagesArray['file'],
                'img' => null
            );
            return response()->json($data);
        }
        $input = Input::all();
        $id = auth()->user()->member->id;
        $getMember = Member::findOrFail($id);
        if(isset($input['file'])) {
            if($getMember->picture !== null){
                $path = public_path('images/'.$getMember->picture);
                unlink($path);
            }
            $file = $request->file('file');
            $fileName = $getMember->nik_koperasi.'.'.$file->getClientOriginalExtension();
            $request->file('file')->move('images/', $fileName);
            $getMember->picture = $fileName;
            $getMember->save();
            $data                =  array(
                'error' => 0,
                'msg'   => 'Pembaharuan foto profile berhasil.',
                'img' => public_path('images/'.$fileName)
            );
            return response()->json($data);
        }

        $data         =  array(
            'error' => 0,
            'msg'   => 'Pembaharuan data pribadi berhasil.',
        );
        return response()->json($data);
    }

}
