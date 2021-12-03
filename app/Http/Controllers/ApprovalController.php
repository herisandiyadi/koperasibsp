<?php

namespace App\Http\Controllers;

use App\Approvals;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\GlobalController;
use Illuminate\Http\Request;

class ApprovalController extends GlobalController
{
    public function index()
    {
        $isCaneViewLoan = auth()->user()->can('view.member.loan');
        $isCaneUpdateLoan = auth()->user()->can('update.member.loan');
        if(auth()->user()->isSu() || auth()->user()->isPow()){
            $selected = Approvals::with('ts_loans.member', 'ts_loans.ms_loans')
                ->select('fk')
                ->groupBy('fk')
                ->get();
        }else{
            $selected = Approvals::where('user_id', auth()->user()->id)->with('ts_loans.member')->where('status', 'menunggu')->get();
        }

        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->ts_loans->member['first_name'];
                })
                ->editColumn('loan_name', function ($selected) {
                    return $selected->ts_loans->ms_loans['loan_name'];
                })
                ->editColumn('loan_number', function ($selected) {
                    return $selected->ts_loans['loan_number'];
                })
                ->editColumn('value', function ($selected) {
                    return 'Rp '. number_format($selected->ts_loans->value);
                })
                ->editColumn('start_date', function ($selected) {
                    return $selected->ts_loans->start_date;
                })
                ->editColumn('end_date', function ($selected) {
                    return $selected->ts_loans->end_date;
                })
                ->editColumn('status', function ($selected) {
                    $status = $selected->ts_loans->approval;
                    if($selected->ts_loans->approval == 'dibatalkan') {
                        $status = 'Telah di dibatalkan';
                    } else if($selected->ts_loans->approval == 'ditolak') {
                        $status = 'Ditolak oleh admin';
                    } else if($selected->ts_loans->approval == 'belum lunas') {
                        $status = 'Belum Lunas';
                    } else if($selected->ts_loans->approval == 'lunas') {
                        $status = 'Lunas';
                    } else if($selected->ts_loans->approval == 'menunggu') {
                        $status = 'Menunggu Persetujuan';
                    }
                    return ucwords($status);
                })
                ->editColumn('action',function($selected) use($isCaneViewLoan, $isCaneUpdateLoan){
                    $idRecord = \Crypt::encrypt($selected->ts_loans->id);
                    $btnReject = '';
                    $btnView = '';
                    $btnUpdate = '';

                    if($isCaneViewLoan){
                        $btnView = '<a  class="btn btn-info btn-sm tooltips" href="/loan-detail/'.$idRecord.'"><i class="ion ion-aperture" title="Lihat pengajuan pinjaman"></i></a>';
                    }

                    if($isCaneUpdateLoan){
                        $btnUpdate = '<a class="btn btn-warning tooltips btn-sm"  onclick="modifyDataLoan('."'revisi-loan'".','."'".$idRecord."'".','."'". csrf_token() ."'".','."'listApprovalLoans'".','."'canceled'".')" data-container="body" data-placement="right" data-html="true" title="Ubah pengajuan" ><i class="fa fa-edit"></i></a>
<a class="btn btn-danger tooltips btn-sm"  onclick="modifyData('."'update-loan'".','."'".$idRecord."'".','."'". csrf_token() ."'".','."'listApprovalLoans'".','."'canceled'".')" data-container="body" data-placement="right" data-html="true" title="Batalkan pengajuan" ><i class="fa fa-undo"></i></a>';
                    }

                    if($selected->ts_loans->approval == 'menunggu') {
                        $action = $btnView . $btnUpdate;
                    } else{
                        $action  = $btnView;
                    }
                    return '<center>'.$action.'</center>';
                })
                ->make(true);
        }
        return view('transaction.approval.loan.list_approval_loan');
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
