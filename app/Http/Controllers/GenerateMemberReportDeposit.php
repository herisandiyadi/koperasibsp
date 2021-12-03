<?php

namespace App\Http\Controllers;

use App\Deposit;
use App\GenerateReportDeposits;
use App\Helpers\DownloadReport;
use App\Helpers\ReverseData;
use App\Helpers\reverseDataHelper;
use App\Member;
use App\TsDeposits;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GenerateMemberReportDeposit extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $indents = [];
        return view('report.generate.deposit-member.new', compact('indents'));
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

    public function getMemberDeposit(Request $request)
    {
        $member_id = $request->get('member_id');

        $start = $request->get('start');
        $end = $request->get('end');
        $dataDepositMember = reverseDataHelper::generateMemberDeposit($member_id, $start, $end);
        $output = '';
        foreach ($dataDepositMember as $deposit) {
            $output .= '<tr>' .
                '<td>' . $deposit['tahun'] . '</td>' .
                '<td>' . $deposit['pokok'] . '</td>' .
                '<td>' . $deposit['wajib'] . '</td>' .
                '<td>' . $deposit['sukarela'] . '</td>' .
                '<td>' . $deposit['shu'] . '</td>' .
                '</tr>';

        }
        return Response($output);
    }

    public function download(Request $request){

        $member_id = $request->get('member_id');
        $start = $request->get('start');
        $end = $request->get('end');
        $member = Member::find($member_id);
        $dataDepositMember = reverseDataHelper::generateMemberDeposit($member_id, $start, $end);
        $spreadsheet = DownloadReport::downloadMemberDeposit($dataDepositMember,$member);
//        return $dataDepositMember;
        $filename = $member->nik_koperasi.'_simpanan.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $path = \Storage::disk('deposit')->path($filename);
        $writer->save($path);
        if(\Storage::disk('deposit')->exists($filename)){
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }
        return redirect()->back();
    }
}
