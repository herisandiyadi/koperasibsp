<?php

namespace App\Http\Controllers;

use App\Bank;
use App\GenerateReportMembers;
use App\Helpers\DownloadReport;
use App\Helpers\reverseDataHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the resources
     */
    public function index()
    {
//        ini_set("memory_limit", "10056M");
        $isCanEdit = auth()->user()->can('edit.member.bank');
        $isCanDelete = auth()->user()->can('delete.member.bank');
        $selected = Bank::all();
        if (request()->ajax()) {
            return \DataTables::of($selected)
                ->editColumn('member', function ($selected) {
                    return $selected->member['first_name'];
                })
                ->editColumn('bank_name', function ($selected) {
                    return $selected->bank_name;
                })
                ->editColumn('bank_account_name', function ($selected) {
                    return $selected->bank_account_name;
                })
                ->editColumn('bank_account_number', function ($selected) {
                    return $selected->bank_account_number;
                })
                ->addColumn('action',function($selected) use ($isCanEdit, $isCanDelete){

                    $btnDelete ='';
                    $btnEdit= '';
                    if($isCanEdit){
                        $btnEdit = '<a class="btn btn-primary btn-sm btnEdit" href="'.url("member/bank/".$selected->id).'/edit"><i class="fa fa-edit"></i></a>';
                    }
                    if($isCanDelete){
                        $btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'bank'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listLevel'".')"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button>';
                    }

                    return '<div style="width: 100px; text-align: center">'.$btnEdit.$btnDelete.'</div>';
                })
                ->make(true);
        }
        return view('members.bank.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('members.bank.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Bank::findOrFail($id);
        return view('members.bank.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        Bank::findOrFail($id)->update($request->all());
        session()->flash('success', trans('response-message.success.update',['object'=>'Bank']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $genDeposit = Bank::findOrFail($id);
        $genDeposit->delete();
        session()->flash('success', trans('response-message.success.delete', ['object'=>'Bank']));
        return redirect()->back();
    }
}
