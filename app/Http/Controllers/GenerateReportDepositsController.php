<?php

namespace App\Http\Controllers;

use App\GenerateReportDeposits;
use App\Helpers\DownloadReport;
use App\Helpers\ReverseDataHelper;
use App\Project;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateReportDepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $selected = GenerateReportDeposits::all();
        if (request()->ajax()) {
            return \DataTables::of($selected)
                ->editColumn('name', function ($selected) {
                    return $selected->name;
                })
                ->editColumn('start', function ($selected) {
                    return $selected->start;
                })
                ->editColumn('end', function ($selected) {
                    return $selected->end;
                })
                ->editColumn('status', function ($selected) {
                    return $selected->status;
                })
                ->addColumn('action',function($selected){
                    $isCanEdit = auth()->user()->can('edit.generate.report.deposit');
                    $isCanDelete = auth()->user()->can('delete.generate.report.deposit');
                    $isCanDownload = auth()->user()->can('view.generate.report.deposit');
                    $btnDelete ='';
                    $btnEdit= '';
                    $btnDownload='';
                    if($isCanDownload)
                    {
                        $btnDownload ='<a class="btn btn-sm btn-warning" href="'.url("generate/deposit-report/".$selected->id).'/download"><i class="fa fa-download"></i></a>';
                    }
                    if($isCanEdit){
                        $btnEdit = '<a class="btn btn-primary btn-sm btnEdit" href="'.url("generate/deposit-report/".$selected->id).'/edit"><i class="fa fa-edit"></i></a>';
                    }
                    if($isCanDelete){
                        $btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'deposit-report'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listLevel'".')"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button>';
                    }

                    return '<center>'.$btnDownload.$btnEdit.$btnDelete.'</center>';
                })
                ->make(true);
        }
        return view('report.generate.deposit.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('report.generate.deposit.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $start = Carbon::createFromFormat('Y-m-d', $request['start']);
        $end = Carbon::createFromFormat('Y-m-d', $request['end']);
        if($start->greaterThan($end))
        {
            session()->flash('errors', collect(['Tanggal Awal dan Akhir Laporan tidak valid']));
            return redirect()->back()->withInput();
        }

        GenerateReportDeposits::create($request->all());
        session()->flash('success', trans('response-message.success.create',['object'=>'Laporan Deposit']));
        return redirect('generate/deposit-report');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GenerateReportDeposits  $generateReportDeposits
     * @return \Illuminate\Http\Response
     */
    public function show(GenerateReportDeposits $generateReportDeposits)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $genDeposit = GenerateReportDeposits::findOrFail($id);
        return view('report.generate.deposit.edit', compact('genDeposit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $start = Carbon::createFromFormat('Y-m-d', $request['start']);
        $end = Carbon::createFromFormat('Y-m-d', $request['end']);
        if($start->greaterThan($end))
        {
            session()->flash('errors', collect(['Tanggal Mulai dan Akhir Laporan tidak valid']));
            return redirect()->back()->withInput();
        }
        GenerateReportDeposits::findOrFail($id)->update($request->all());
        session()->flash('success', trans('response-message.success.update',['object'=>'Laporan Deposit']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $genDeposit = GenerateReportDeposits::findOrFail($id);
        $genDeposit->delete();
        session()->flash('success', trans('response-message.success.delete', ['object'=>'Laporan Deposit']));
        return redirect()->back();
    }

    public function download($id){
        $genDeposit = GenerateReportDeposits::findOrFail($id);
        $genReportDeposit = DownloadReport::generateDeposit($genDeposit->start, $genDeposit->end);
        $spreadsheet = ReverseDataHelper::downloadDeposit($genReportDeposit, $genDeposit);
        $filename = $genDeposit->id.'_simpanan.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $path = \Storage::disk('deposit')->path($filename);
        $writer->save($path);
        if(\Storage::disk('deposit')->exists($filename)){
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }
        return $genReportDeposit;
    }
}
