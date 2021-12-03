<?php

namespace App\Http\Controllers;

use App\TotalDepositMember;
use App\User;
use Auth;
use Carbon\Carbon;
use Redirect;
use Validator;
use App\Member;
use App\TsDeposits;
use App\TsDepositsDetail;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class TsDepositsController extends GlobalController
{
    public function index(){

        $selected = TsDeposits::getAllDepositArea(auth()->user()->region);
        $totalWajib = TsDeposits::getDepositAreaWajib(auth()->user()->region);
        $totalPokok = TsDeposits::getDepositAreaPokok(auth()->user()->region);
        $totalSukarela = TsDeposits::getDepositAreaSukarela(auth()->user()->region);
        $totalShu = TsDeposits::getDepositAreaShu(auth()->user()->region);
        $totalLainnya = TsDeposits::getDepositAreaLainnya(auth()->user()->region);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('member', function ($selected) {
                return $selected->member->first_name;
			})
			->editColumn('total_deposit', function ($selected){
				return 'Rp. '. number_format($selected->total_deposit);
			})
            ->editColumn('type', function ($selected){
                return $selected->ms_deposit->deposit_name;
            })
            ->editColumn('date', function ($selected){
                return Carbon::parse($selected->post_date)->format('Y-m-d');
            })
            ->editColumn('status', function ($selected) {
                $status = $selected->status;
                if($status == 'paid') {
                   $status         = 'Lunas';
                } else if($status == 'pending') {
                   $status         = 'Masih pending';
                } else if($status == 'unpaid' ) {
                   $status             = 'Belum Lunas';
                }
                return ucwords($status);
            })
            ->addColumn('action',function($selected){
                $idRecord = \Crypt::encrypt($selected->id);
                return
                '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
            })
            ->make(true);
		}
		// return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts_deposit_list', compact('totalPokok', 'totalWajib', 'totalLainnya', 'totalSukarela', 'totalShu'));
	}

    public function pokok(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 1);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-pokok');
    }

    public function wajib(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 2);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-wajib');
    }

    public function sukarela(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 3);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-sukarela');
    }

    public function berjangka(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 4);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-berjangka');
    }

    public function shu(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 5);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-shu');
    }

    public function lainnya(){

        $selected = TsDeposits::getDepositTypeArea(auth()->user()->region, 6);
//        if(auth()->user()->isSu() || auth()->user()->isPow()){
//            $selected = TsDeposits::get();
//        }else{
//            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'] .' '. $selected->member['first_name'];
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('date', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
                })
                ->make(true);
        }
        // return $selected[0]->member['first_name'];
        return view('transaction.deposit.ts-deposit-list-lainnya');
    }


	public function ts_deposit_members(){


        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id)->get();
            $totalWajib = TsDeposits::totalDepositWajib(auth()->user()->member->id);
            $totalPokok = TsDeposits::totalDepositPokok(auth()->user()->member->id);
            $totalSukarela = TsDeposits::totalDepositSukarela(auth()->user()->member->id);
            $totalShu = TsDeposits::totalDepositShu(auth()->user()->member->id);
            $totalLainnya = TsDeposits::totalDepositLainnya(auth()->user()->member->id);
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('member', function ($selected){
                return $selected->member->first_name;
            })
            ->editColumn('type', function ($selected){
                return $selected->ms_deposit->deposit_name;
            })
            ->editColumn('deposit_number', function ($selected) {
                return $selected->deposit_number;
            })
			->editColumn('total_deposit', function ($selected){
				return 'Rp. '. number_format($selected->total_deposit);
			})
            ->editColumn('status', function ($selected) {
                $status = $selected->status;
                if($status == 'paid') {
                   $status         = 'Lunas';
                } else if($status == 'pending') {
                   $status         = 'Masih pending';
                } else if($status == 'unpaid' ) {
                   $status             = 'Belum Lunas';
                }
                return ucwords($status);
            })
            ->addColumn('action',function($selected){
                $idRecord              = \Crypt::encrypt($selected->id);
                return
                '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
            })
            ->make(true);
		}
        return view('members.deposit.deposit', compact('totalWajib', 'totalPokok', 'totalSukarela', 'totalShu', 'totalLainnya'));
	}

	public function ts_deposit_members_detail($el){
        $depDetail = $this->decrypter($el);
        $selected  = TsDepositsDetail::where('transaction_id', $depDetail)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('transaction_id', function ($selected) {
                return $selected->deposit->deposit_number;
            })
            ->editColumn('total', function ($selected){
                return 'Rp. '. number_format($selected->total);
            })
            ->editColumn('status', function ($selected) {
                $status = $selected->status;
                if($status == 'paid') {
                   $status           = 'Lunas';
                } else if($status   == 'pending') {
                   $status           = 'Masih pending';
                } else if($status == 'unpaid' || $selected->status == '') {
                   $status             = 'Belum Lunas';
                }
                return ucwords($status);
            })
            ->make(true);
        }
     return view('members.deposit.deposit-detail', compact('el'));
    }
    public function depositDetail($el){
        $depDetail = $this->decrypter($el);
		$selected  = TsDepositsDetail::where('transaction_id', $depDetail)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('transaction_id', function ($selected) {
                return $selected->deposit->deposit_number;
            })
            ->editColumn('total', function ($selected){
                return 'Rp. '. number_format($selected->total);
            })
            ->editColumn('status', function ($selected) {
                $status = $selected->status;
                if($status == 'paid') {
                   $status           = 'Lunas';
                } else if($status   == 'pending') {
                   $status           = 'Masih pending';
                } else if($status == 'unpaid' || $selected->status == '') {
                   $status             = 'Belum Lunas';
                }
                return ucwords($status);
            })
            ->addColumn('action',function($selected){
                $idRecord              = \Crypt::encrypt($selected->id);
                return
                '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Perbaharui </a>
                </center>';
            })
            ->make(true);
        }
     return view('members.deposit.view-detail', compact('el'));
	}
    public function viewDeposit()
    {
        $input           = Input::all();
        $idRecord        = $this->decrypter($input['id']);
        $selected        = TsDeposits::findOrFail($idRecord);
        if($selected){
            $detail      = TsDepositsDetail::where('transaction_id', $idRecord)->get();
            $data        = array(
                                'error'    => 0,
                                'msg'      => 'Berhasil.',
                                'name_dps' => $selected->ms_deposit->deposit_name,
                                'json'     => $selected,
                                'detail'   => $detail,
                            );
            } else{
            $data        = array(
                                'error' => 1,
                                'msg'   => 'Data cicilan tidak ditemukan.',
                            );
         }
        return response()->json($data);
    }
    public function updateDeposit()
    {
        $input           = Input::all();
        $selected        = TsDeposits::findOrFail($input['id']);

        if($selected){
            if($selected['status'] === 'paid'){
                $data     = array(
                    'error'    => 1,
                    'msg'      => 'Mohon maaf data simpanan ini sudah terbayar.',
                );
            }else{
                $selected->status = $input['status'];
                $selected->save();

                if($input['status'] === 'paid'){
                    $totalDepositMember = TotalDepositMember::where([
                        'member_id' => $selected['member_id'],
                        'ms_deposit_id' => $selected['ms_deposit_id']
                    ])->first();

                    $value = $totalDepositMember['value'] + $selected['total_deposit'];
                    $totalDepositMember->value = $value;
                    $totalDepositMember->save();
                }

                $data     = array(
                                    'error'    => 0,
                                    'msg'      => 'Data Berhasil diperbaharui.',
                                    'json'     => $selected,
                                );
            }
            } else{
            $data     = array(
                                'error' => 1,
                                'msg'   => 'Data simpanan tidak ditemukan.',
                            );
         }
        return response()->json($data);
    }

    public function ts_deposit_members_wajib(){
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id )->fTypeDeposit(2)->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
                })
                ->make(true);
        }
        return view('members.deposit.deposit-wajib');
    }

    public function ts_deposit_members_pokok(){
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id )->fTypeDeposit(1)->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
                })
                ->make(true);
        }
        return view('members.deposit.deposit-pokok');
    }

    public function ts_deposit_members_sukarela(){
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id )->fTypeDeposit(3)->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
                })
                ->make(true);
        }
        return view('members.deposit.deposit-sukarela');
    }

    public function ts_deposit_members_shu(){
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id )->fTypeDeposit(5)->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
                })
                ->make(true);
        }
        return view('members.deposit.deposit-shu');
    }

    public function ts_deposit_members_lainnya(){
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = TsDeposits::get();
        }else{
            $selected = TsDeposits::where('member_id', auth()->user()->member->id )->fTypeDeposit(6)->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('total_deposit', function ($selected){
                    return 'Rp. '. number_format($selected->total_deposit);
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->status;
                    if($status == 'paid') {
                        $status         = 'Lunas';
                    } else if($status == 'pending') {
                        $status         = 'Masih pending';
                    } else if($status == 'unpaid' ) {
                        $status             = 'Belum Lunas';
                    }
                    return ucwords($status);
                })
                ->addColumn('action',function($selected){
                    $idRecord              = \Crypt::encrypt($selected->id);
                    return
                        '<center>
                <a  class="btn btn-info btn-sm btnEdit" onclick="showRecord('."'".$idRecord."'".','."'". csrf_token() ."'".','."'". $selected->status ."'".')"><i class="glyphicon glyphicon-list-alt"></i>  Detail </a>
                </center>';
                })
                ->make(true);
        }
        return view('members.deposit.deposit-lainnya');
    }

    public function updateDepositMember(){
        ini_set("memory_limit", "10056M");
        $members = Member::select('id', 'first_name')->get();

        foreach ($members as $member){
//            TotalDepositMember::where('member_id', $member->id)->delete();
            #pokok
            $totalPokok = TsDeposits::totalDepositPokok($member->id);
            if($totalPokok < 0){
                $totalPokok = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 1;
            $totalDepositMember->value = $totalPokok;
            $totalDepositMember->save();

            #sukarela
            $totalSukarela = TsDeposits::totalDepositSukarela($member->id);
            if($totalSukarela < 0){
                $totalSukarela = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 3;
            $totalDepositMember->value = $totalSukarela;
            $totalDepositMember->save();

            #wajib
            $totalWajib = TsDeposits::totalDepositWajib($member->id);
            if($totalWajib < 0){
                $totalWajib = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 2;
            $totalDepositMember->value = $totalWajib;
            $totalDepositMember->save();

            #lainnya
            $totalLainnya = TsDeposits::totalDepositLainnya($member->id);
            if($totalLainnya < 0){
                $totalLainnya = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 6;
            $totalDepositMember->value = $totalLainnya;
            $totalDepositMember->save();

            #shu
            $totalShu = TsDeposits::totalDepositShu($member->id);
            if($totalShu < 0){
                $totalShu = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 5;
            $totalDepositMember->value = $totalShu;
            $totalDepositMember->save();

            #berjangka
            $totalBerjangka = TsDeposits::totalDepositBerjangka($member->id);
            if($totalBerjangka < 0){
                $totalBerjangka = 0;
            }
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member->id;
            $totalDepositMember->ms_deposit_id = 4;
            $totalDepositMember->value = $totalBerjangka;
            $totalDepositMember->save();
        }

        return 'updated';
    }

    public function updateProjectMember(){
        ini_set("memory_limit", "10056M");
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/update_project_member.csv';
        $csv = utf8_encode(file_get_contents($file));
        $array = explode("\r", $csv);
        $data = array_map('str_getcsv', $array);

        $csv_data = array_slice($data, 0, 5000);
        foreach ($csv_data as $key => $val) {
            $iProject = str_replace("ï»¿", '', $val);
            $user = User::where('email', $iProject[2].'@gmail.com')->first();
            $member = $user->member;
            $member->project_id = $iProject[0];
            $member->save();
        }


        return 'updated';
    }
}
