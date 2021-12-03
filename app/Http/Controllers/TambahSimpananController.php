<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TsDeposits;
use App\TsDepositsDetail;
class TambahSimpananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indents = [];
        return view('tambah-simpanan.new', compact('indents'));
    }

    public function getMember(Request $request)
    {
        if ($request->has('q')) {
            $cari = $request->get('q');
            $member = Member::FActive()->select('id','first_name','nik_koperasi','nik_bsp')
                ->where('first_name', 'LIKE', '%'.$cari.'%')
                ->orWhere('nik_koperasi', $cari)
                ->orWhere('nik_bsp', $cari)
                ->get();

            return response()->json($member);
        }

        return [];
    }


    public function store(Request $request)
    {
		$global = new GlobalController();
		$deposit = new TsDeposits();
        $deposit->member_id = $request->member_id;
        $deposit->deposit_number = $global->getDepositNumber();
        $deposit->ms_deposit_id = 3;
        $deposit->type = 'debit';
        $deposit->deposits_type = 'sukarela';
        $deposit->total_deposit = abs($request->nominal);
        $deposit->post_date = $request->post_date;
		$deposit->desc = $request->desc;
		$deposit->status = 'paid';
        $deposit->save();

        $deposit_detail = new TsDepositsDetail();
        $deposit_detail->transaction_id = $deposit->id;
        $deposit_detail->deposits_type = 'sukarela';
		$deposit_detail->debit = abs($request->nominal);
		$deposit_detail->credit = 0;
        $deposit_detail->total = abs($request->nominal);
        $deposit_detail->status = $deposit->status;
        $deposit_detail->payment_date = $request->post_date;
        $deposit_detail->save();

		return redirect('/tambah-simpanan');
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
