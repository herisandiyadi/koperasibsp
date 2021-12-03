<?php

namespace App\Http\Controllers;

use App\TsLoans;
use App\TsLoansDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberDetailLoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $selected = TsLoans::where('member_id', $id)->with('ms_loans')->get();
//        dd($selected);
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('jenis_pinjaman', function ($selected) {
                    return $selected->ms_loans->loan_name;
                })
                ->editColumn('total', function ($selected) {
                    return $selected->value;
                })
                ->editColumn('start', function ($selected) {
                    return $selected->start_date;
                })
                ->editColumn('end', function ($selected) {
                    return $selected->end_date;
                })
                ->editColumn('period', function ($selected) {
                    return $selected->period;
                })
                ->editColumn('in_period', function ($selected) {
                    return $selected->in_period;
                })
                ->editColumn('status', function ($selected) {
                    return $selected->approval;
                })
                ->editColumn('action', function ($selected) {
                    $idRecord              = \Crypt::encrypt($selected->id);
                    if($selected->approval == 'menunggu') {
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
        return view('detail-loan.list', compact('id'));
    }

    public function getDetaiList($id){
        $selected = TsLoansDetail::where('loan_id', $id)->get();
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('loan_number', function ($selected) {
                    return $selected->loan_number;
                })
                ->editColumn('total', function ($selected) {
                    return $selected->value;
                })
                ->editColumn('service', function ($selected) {
                    return $selected->service;
                })
                ->editColumn('pay_date', function ($selected) {
                    return $selected->pay_date;
                })
                ->editColumn('in_period', function ($selected) {
                    return $selected->in_period;
                })
                ->editColumn('status', function ($selected) {
                    return $selected->approval;
                })
                ->editColumn('action', function ($selected) {
                    return '<a  class="btn btn-info btn-sm" href="/get-detail-deposit/'.$selected->id.'"  data-toggle="tooltip" title="Detail Deposit">Detail</a>';
                })
                ->make(true);
        }
        return view('detail-loan.detail-list', compact('id'));
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
