<?php

namespace App\Http\Controllers\Panel;

use App\Approvals;
use App\Helpers\cutOff;
use App\Notifications\LoanApplicationStatusUpdated;
use App\Notifications\LoanApprovalNotification;
use Auth;
use App\Loan;
use App\Member;
use App\Region;
use App\TsLoans;
use \Carbon\Carbon;
use App\TsLoansDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\GlobalController;
use DB;

class TsLoansController extends GlobalController
{
    public function index()
    {

        $isCaneViewLoan = auth()->user()->can('view.member.loan');
        $isCaneUpdateLoan = auth()->user()->can('update.member.loan');

        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsLoans::get();
        }else{
            $selected = TsLoans::where('member_id', auth()->user()->member->id)->get();
        }

        $this->i  = 1;
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('member', function ($selected) {
                return $selected->member['first_name'];
			})
			->editColumn('loan_type', function ($selected) {
                return $selected->ms_loans['loan_name'];
            })
            ->editColumn('no', function ($selected) {
                return $this->i++;
            })
            ->editColumn('loan_number', function ($selected) {
                return $selected->loan_number;
            })
            ->editColumn('value', function ($selected) {
                return 'Rp '. number_format($selected->value);
            })
            ->editColumn('status', function ($selected) {
                $status = $selected->approval;
                if($selected->approval == 'dibatalkan') {
                    $status = 'Telah di dibatalkan';
                } else if($selected->approval == 'ditolak') {
                    $status = 'Ditolak oleh admin';
                } else if($selected->approval == 'belum lunas') {
                    $status = 'Belum Lunas';
                } else if($selected->approval == 'lunas') {
                    $status = 'Lunas';
                } else if($selected->approval == 'menunggu') {
                    $status = 'Menunggu Persetujuan';
                }
                return ucwords($status);
            })
            ->addColumn('action',function($selected){
            	$idRecord              = \Crypt::encrypt($selected->id);
            	if($selected->approval == '') {
            		$action            = '<a  class="btn btn-info btn-sm tooltips" href="/loan-detail/'.$idRecord.'"><i class="ion ion-aperture" title="Liat pengajuan pinjaman"></i></a>
                                         <a class="btn btn-danger tooltips btn-sm"  onclick="modifyData('."'update-loan'".','."'".$idRecord."'".','."'". csrf_token() ."'".','."'listTsLoans'".','."'canceled'".')" data-container="body" data-placement="right" data-html="true" title="Batalkan pengajuan" ><i class="fa fa-undo"></i></a>';
            	} else{
            		$action            = '<a  class="btn btn-info btn-sm tooltips" href="/loan-detail/'.$idRecord.'"><i class="ion ion-aperture" title="Liat pengajuan pinjaman"></i></a>';
            	}
                return
                '<center>'.$action.'</center>';
            })
            ->make(true);
        }
        return view('transaction.loan.ts_loan_list');
    }
    public function loanDetail($el='')
    {
        $idRecord            = $this->decrypter($el);
        $finder              = TsLoans::findOrFail($idRecord);
		$member = $finder->member;

		$loan = TsLoans::where('loan_id',1)->where('member_id',$member->id)->where('approval', 'lunas');
		$totalLoan = $loan->count();
		$lastLoan = $loan->orderBy('value', 'desc')->limit(1)->first();
		$sisa_pinjaman = DB::table('ts_loan_details')->where('loan_id', $idRecord)->where('approval', 'belum lunas')->sum('value');

        if($finder->approval == 'disetujui') {
            return view('transaction.loan.loan-detail-approved', compact('finder', 'totalLoan', 'lastLoan', 'sisa_pinjaman'));
        }else {
            return view('transaction.loan.loan-detail-approved', compact('finder', 'totalLoan', 'lastLoan', 'sisa_pinjaman'));
        }
    }
    public function detailApproved()
    {
        $input        = Input::all();
        $loan_id      = $this->decrypter($input['loan_id']);
        $findDetail   = TsLoansDetail::where('loan_id',$loan_id)->orderBy('in_period')->get();
        if($findDetail){
            $data     = array(
                            'error' => 0,
                            'msg'   => 'Data ditemukan.',
                            'json'   => $findDetail,
                        );
        } else{
            $data     = array(
                            'error' => 1,
                            'msg'   => 'Data cicilan tidak ditemukan.',
                        );
        }
        return response()->json($data);

    }
    public function updateLoan()
    {
    	$input = Input::all();
        $reason = $input['reason'];
        $id = $this->decrypter($input['id']);

        if(Auth::user()->position->name == 'superadmin' || Auth::user()->position->name == 'power') {
          $findData  = TsLoans::where([
                        'id'        => $id,
                        ])->first();
       } else {
          $findData  = TsLoans::where([
                        'id'        => $id,
                        'member_id' => Auth::user()->member->id,
                        ])->first();
       }
    	if ($findData){
            if($input['action'] == 'approved') {
                $toDate = $this->dateTime(now(), 'full');
                $getRate = $findData->value * ($findData->rate_of_interest / 100);
                $getMonthly = ($findData->value + $getRate) / $findData->period;
                $getMonthly = (int)round($getMonthly);
                for ($i = 0; $i < $findData->period; $i++) {
                    // insert into table loan detail
                    $detailID = $this->getLoanNumber();
                    $nextMonth = $toDate->addMonths(1)->format('Y-m-d');
                    $prev = date('Y-m-d', strtotime($nextMonth . ' -1 months'));
                    $data = new TsLoansDetail();
                    $data->id = $data::max('id') + 1;
                    $data->loan_id = $findData->id;
                    $data->loan_number = $detailID;
                    $data->value = $getMonthly;
                    $data->pay_date = $nextMonth;
                    $data->in_period = $i + 1;
                    $data->approval = 'disetujui';
                    $data->save();
                }
                $findData->start_date = $this->dateTime(now(), 'date');
                $findData->end_date = $nextMonth;
                $findData->approval = 'disetujui';
                $findData->save();
                $data = 'Success';

            }

            if($input['action'] == 'canceled'){
                if($findData->approval == 'ditolak' || $findData->approval == 'disetujui' || $findData->approval == 'lunas' || $findData->approval == 'belum lunas'){
                    $data = 'Failed';
                }else {
                    $findData->approval = 'ditolak';
                    $findData->notes = $reason;
                    $findData->save();
                    TsLoansDetail::where('loan_id', $findData->id)->update([
                        'approval' => 'ditolak'
                    ]);
                    if(auth()->user()->isSu() || auth()->user()->isPow()){
                        Approvals::where('fk', $findData->id)->update([
                            'status' => 'ditolak'
                        ]);
                    }else{
                        Approvals::where([
                            'fk' => $findData->id,
                            'user_id' => auth()->user()->id
                        ])->update([
                            'status' => 'ditolak'
                        ]);
                    }

                    $data = 'Success';
                }
            }
         }
         // NOTIFY APPLICANT
         $findData->member->user->notify(new LoanApplicationStatusUpdated($findData));

         return response()->json($data);
    }

    public function revisiLoan()
    {
        $input = Input::all();
        $id = $this->decrypter($input['id']);
        $findData  = TsLoans::where(['id' => $id])->first();
        return response()->json($findData);
    }

    public function updateRevisiLoan(Request $request){
        $input = Input::all();
        $loan_id = $input['loan_id'];
        $value = $input['value'];
        $biaya_admin = $input['biaya_admin'];
        $biaya_transfer = $input['biaya_transfer'];
        $keterangan = $input['description'];

        $tsLoans = TsLoans::with('ms_loans', 'detail')->find($loan_id);
        $tsLoans->member->user->notify(new LoanApprovalNotification($tsLoans, 'direvisi', $value, $tsLoans->value));
        $period = $tsLoans->period;
        $loan_value = $value / $period;
        $loan_value = ceil($loan_value);
        $biayaJasa = ceil($value * ($tsLoans->ms_loans->rate_of_interest/100));
        $biayaProvisi = ceil($value * ($tsLoans->ms_loans->provisi / 100));
        $biayaBungaBerjalan = cutOff::getBungaBerjalan($loan_value, $tsLoans->ms_loans->biaya_bunga_berjalan, now()->format('Y-m-d'));

        $tsLoans->value = $value;
        $tsLoans->biaya_admin = $biaya_admin;
        $tsLoans->biaya_transfer = $biaya_transfer;
        $tsLoans->notes = $keterangan;
        $tsLoans->biaya_jasa = $biayaJasa;
        $tsLoans->biaya_provisi = $biayaProvisi;
        $tsLoans->biaya_bunga_berjalan = $biayaBungaBerjalan;
        $tsLoans->approval = 'direvisi';
        $tsLoans->save();

        foreach ($tsLoans->detail as $loan_detail){
            $service = $biayaJasa / $period;
            $loan_detail->value = $loan_value;
            $loan_detail->service = $service;
            $loan_detail->approval = 'direvisi';

            $loan_detail->save();
        }

        return redirect('persetujuan-pinjaman')->with('success', 'Perubahan pinjaman berhasil');

    }

    public function agreeRevisiLoan(Request $request){
        $input = Input::all();
        $loan_id = \Crypt::decrypt($input['loan_id']);
        $tsLoans = TsLoans::with('ms_loans', 'detail')->find($loan_id);
        $tsLoans->approval = 'belum lunas';
        $tsLoans->save();
        foreach ($tsLoans->detail as $loan_detail){
            $loan_detail->approval = 'belum lunas';
            $loan_detail->save();
        }
        return [
            'data' => 1
        ];

    }

    public function getLoans()
    {
        $selected = TsLoans::getLoanArea(auth()->user()->region);

        $this->i  = 1;
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('member', function ($selected) {
                return $selected->member['first_name'];
            })
            ->editColumn('loan_type', function ($selected) {
                return $selected->ms_loans['loan_name'];
            })
            ->editColumn('laon_number', function ($selected) {
                return $selected->loan_number;
            })
            ->editColumn('value', function ($selected) {
                return 'Rp '. number_format($selected->value);
            })
            ->editColumn('status', function ($selected) {
                if($selected->approval == 'dibatalkan') {
                   $status = 'Telah di dibatalkan';
                } else if($selected->approval == 'ditolak') {
                   $status = 'Ditolak oleh admin';
                } else if($selected->approval == 'belum lunas') {
                   $status = 'Belum Lunas';
                } else if($selected->approval == 'lunas') {
                   $status = 'Lunas';
                } else if($selected->approval == 'menunggu') {
                    $status = 'Menunggu Persetujuan';
                }
                return ucwords($status);
            })

            ->addColumn('action',function($selected){
                $idRecord              = \Crypt::encrypt($selected->id);
                if($selected->approval == 'menunggu') {
                    $action            = '<a  class="btn btn-info btn-sm tooltips" href="/loan-detail/'.$idRecord.'"><i class="ion ion-aperture" title="Liat pengajuan pinjaman"></i></a>
                                          <a class="btn btn-danger tooltips btn-sm"  onclick="modifyData('."'update-loan'".','."'".$idRecord."'".','."'". csrf_token() ."'".','."'listTsLoansAll'".','."'rejected'".')" data-container="body" data-placement="right" data-html="true" title="Batalkan pengajuan" ><i class="fa fa-undo"></i></a>
                                          <a class="btn btn-success tooltips btn-sm"  onclick="modifyData('."'update-loan'".','."'".$idRecord."'".','."'". csrf_token() ."'".','."'listTsLoansAll'".','."'approved'".')" data-container="body" data-placement="right" data-html="true" title="Setujui peminjaman" ><i class="fa fa-check"></i></a>';
                } else{
                    $action            = '<a  class="btn btn-info btn-sm tooltips" href="/loan-detail/'.$idRecord.'"><i class="ion ion-aperture" title="Liat pengajuan pinjaman"></i></a>';
                }
                return
                '<div style="min-width: 120px">'.$action.'</div>';
            })
            ->make(true);
        }
        return view('transaction.loan.ts_loan_all');
    }
    public function addTenor()
    {
        $input           = Input::all();
        if(isset($input['specific'])){
            $id          = $input['loan_id'];
            $getDetail   = TsLoansDetail::find($id);
            if($getDetail){
                    $getLoans = TsLoans::find($getDetail->loan_id);
                    $data     = array(
                                    'error'    => 0,
                                    'msg'      => 'Berhasil.',
                                    'ms_loans' => $getLoans->ms_loans,
                                    'json'     => $getDetail,
                                );
                } else{
                    $data     = array(
                                    'error' => 1,
                                    'msg'   => 'Data cicilan tidak ditemukan.',
                                );
            }
            return response()->json($data);
        } else {
            $id          = $this->decrypter($input['loan_id']);
            $getDetail   = TsLoansDetail::where('loan_id', $id)
                           ->orderBy('in_period', 'pay_date')
                           ->first();
            $getLoans = TsLoans::find($getDetail->loan_id);
            if($getDetail){
                $service = $getLoans->value * ($getLoans->ms_loans->rate_of_interest / 100);
                // generate new Nomor Pinjaman
                $getLoanNumber     = $this->getLoanNumber();
                $period            = Carbon::parse($getDetail->pay_date);
                $nextMonth         = $period->addMonths(1)->format('Y-m-d');
                $data              = new TsLoansDetail();
                $data->id          = $data::max('id')+1;
                $data->value       = $input['nominal'];
                $data->service     = $service;
                $data->loan_id     = $id;
                $data->pay_date    = $nextMonth;
                $data->loan_number = $getLoans->loan_number;
                $data->in_period   = $getDetail->in_period + 1;
                $data->approval      = 'belum lunas';
                $data->save();

                ++$getLoans->period;
                ++$getLoans->add_period;
                $getLoans->save();
                // get detail loan
                $findDetail   = TsLoansDetail::where('loan_id',$id)->orderBy('in_period')->get();
                if($findDetail){
                    $data     = array(
                                    'error' => 0,
                                    'msg'   => 'Penambahan Tenor baru berhasil.',
                                    'json'   => $findDetail,
                                );
                } else{
                    $data     = array(
                                    'error' => 1,
                                    'msg'   => 'Data cicilan tidak ditemukan.',
                                );
                }
                return response()->json($data);
            }
        }
    }
	public function changeStatus()
    {
        $input         = Input::all();
        $detail_id     = $input['detail_id'];
        $getDetail     = TsLoansDetail::find($detail_id);
        $loan = TsLoans::where('id', $getDetail->loan_id)->first();
        if($input['status'] == 'belum lunas' || $input['status'] == 'lunas') {
            if ($getDetail) {
                $getDetail->approval = $input['status'];
                if($input['status'] == 'lunas'){
                    $loan->in_period = $getDetail->in_period;
                    $loan->save();
                }
                if($input['status'] == 'belum lunas'){
                    $loan->in_period = $getDetail->in_period - 1;
                    $loan->save();
                }
                $getDetail->save();

            } else {
                $data      = array(
                                'error' => 1,
                                'msg'   => 'Gagal memperbaharui status data.',
                            );
                return response()->json($data);

            }
        } else {
            // rule untuk penangguhan pembayaran
            // update value jadi 0
            // add value to the next month
            if (!empty($getDetail)) {
                $value             = $getDetail->value;
                $tenor             = $getDetail->in_period;
                // find for tenor next month
                $findTenor         = TsLoansDetail::where(['in_period' => $tenor, 'loan_id' => $getDetail->loan_id])->first();
                if($findTenor) {
                    // 0 value
                    $getDetail->approval = $input['status'];
                    $getDetail->value  = 0;
                    $getDetail->service  = 0;
                    // add more value
                    $findTenor->value  = $value + $findTenor->value;
                    // save data
                    $findTenor->save();
                    $getDetail->save();
                } else {
                    $data  = array(
                                'error' => 1,
                                'msg'   => 'Maaf, cicilan untuk bulan depan tidak ditemukan. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
                            );
                    return response()->json($data);

                }

            } else {
                $data      = array(
                                'error' => 1,
                                'msg'   => 'Gagal memperbaharui status data.',
                            );
                return response()->json($data);

            }
        }
        // get all data again
        $findDetail    = TsLoansDetail::where('loan_id',$getDetail->loan_id)->orderBy('in_period')->get();
        if($findDetail){
            $data      = array(
                            'error' => 0,
                            'msg'   => 'Data berhasil diperbaharui.',
                            'json'   => $findDetail,
                        );
        } else{
            $data      = array(
                            'error' => 1,
                            'msg'   => 'Gagal memperbaharui status data.',
                        );
        }
        return response()->json($data);
    }
    public function updateStatus()
    {
        $input         = Input::all();
        $loan_id       = $this->decrypter($input['loan_id']);
        $getLoan       = TsLoans::findOrFail($loan_id);
        if($input['status'] == 'belum lunas' || $input['status'] == 'lunas') {
            if ($getLoan) {
                if($input['status'] == 'belum lunas'){
                    $getLoan->approval = 'lunas';
                } else if($input['status'] == 'lunas'){
                    $getLoan->approval = 'disetujui';
                }
                // update to lunas
                $getLoan->in_period = $getLoan->period;
                $getLoan->approval    = $input['status'];
                $getLoan->save();
                // update detail loan
                TsLoansDetail::where(['loan_id' => $loan_id])->update(['approval' => $input['status']]);
                $data      = array(
                            'error' => 0,
                            'msg'   => 'Data berhasil diperbaharui.',
                        );
            } else {
                $data      = array(
                                'error' => 1,
                                'msg'   => 'Gagal memperbaharui status data.',
                            );
                return response()->json($data);

            }
        }
        return response()->json($data);
    }
}
