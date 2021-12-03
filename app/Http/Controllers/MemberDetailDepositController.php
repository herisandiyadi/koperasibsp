<?php

namespace App\Http\Controllers;

use App\Deposit;
use App\TotalDepositMember;
use App\TsDeposits;
use App\TsDepositsDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberDetailDepositController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $selected = TsDeposits::where('member_id', $id)->get();
        $totalWajib = TsDeposits::totalDepositWajib($id);
        $totalPokok = TsDeposits::totalDepositPokok($id);
        $totalSukarela = TsDeposits::totalDepositSukarela($id);
        $totalShu = TsDeposits::totalDepositShu($id);
        $totalLainnya = TsDeposits::totalDepositLainnya($id);
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('deposit_number', function ($selected) {
                    return $selected->deposit_number;
                })
                ->editColumn('date', function ($selected) {
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
                })
                ->editColumn('total', function ($selected) {
                    return $selected->total_deposit;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
                ->editColumn('status', function ($selected) {
                    return $selected->status;
                })
                ->editColumn('keterangan', function ($selected) {
                    return $selected->desc;
                })
                ->editColumn('action', function ($selected) {
                   return '<a  class="btn btn-info btn-sm" href="/get-detail-deposit/'.$selected->id.'"  data-toggle="tooltip" title="Detail Deposit">Detail</a>';
                })
                ->make(true);
        }
        return view('detail-deposit.list', compact('id','totalWajib', 'totalPokok', 'totalSukarela', 'totalShu', 'totalLainnya'));
    }

    public function getDetaiList($id){
        $selected = TsDepositsDetail::where('transaction_id', $id)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('debit', function ($selected) {
                    return $selected->debit;
                })
                ->editColumn('credit', function ($selected) {
                    return $selected->credit;
                })
                ->editColumn('action', function ($selected) {
                    return '<a  class="btn btn-info btn-sm" href="#"  data-toggle="tooltip" title="Detail Deposit">Edit</a>';
                })
                ->make(true);
        }
        return view('detail-deposit.detail-list', compact('id'));
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

    public function ts_deposit_members_wajib($id){
        $selected = TsDeposits::where('member_id', $id)->fTypeDeposit(2)->get();

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

    public function ts_deposit_members_pokok($id){
        $selected = TsDeposits::where('member_id', $id)->fTypeDeposit(1)->get();

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

    public function ts_deposit_members_sukarela($id){

        $selected = TsDeposits::where('member_id', $id)->orderBy('post_date','ASC')->fTypeDeposit(3)->get();

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected){
                    return $selected->member->first_name;
                })
                ->editColumn('type', function ($selected){
                    return $selected->ms_deposit->deposit_name;
                })
				->editColumn('deposit_type', function ($selected){
                    return $selected->type;
                })
				->editColumn('tanggal', function ($selected){
                    return Carbon::parse($selected->post_date)->format('Y-m-d');
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

    public function ts_deposit_members_shu($id){
        $selected = TsDeposits::where('member_id', $id)->fTypeDeposit(5)->get();

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

    public function ts_deposit_members_lainnya($id){
        $selected = TsDeposits::where('member_id', $id)->fTypeDeposit(6)->get();

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
}
