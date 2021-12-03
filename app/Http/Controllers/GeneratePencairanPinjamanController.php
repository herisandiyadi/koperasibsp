<?php

namespace App\Http\Controllers;

use App\Helpers\DownloadReport;
use App\Helpers\reverseDataHelper;
use App\Member;
use Illuminate\Http\Request;

class GeneratePencairanPinjamanController extends Controller
{
    public function index()
    {
        $indents = [];
        return view('report.generate.pencairan-pinjaman.new', compact('indents'));
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
        $dataDepositMember = reverseDataHelper::generateMemberDepositSukarela($member_id, $start, $end);
//        dd($dataDepositMember);
        $output = '';
        foreach ($dataDepositMember as $deposit) {
            $output .= '<tr>' .
                '<td>' . $deposit['tahun'] . '</td>' .
                '<td>' . $deposit['masuk'] . '</td>' .
                '<td>' . $deposit['keluar'] . '</td>' .
                '<td>' . $deposit['saldo'] . '</td>' .
                '</tr>';

        }
        return Response($output);
    }

    public function download(Request $request)
    {


        $start = $request->get('start');
        $end = $request->get('end');

        $filename = 'pencairan_pinjaman.xlsx';
        $path = \Storage::disk('template')->path($filename);
        if(\Storage::disk('template')->exists('pencairan_pinjaman.xlsx')){
            return response()->download($path, $filename);
        }
        return redirect()->back();

//        $dataDepositMember = reverseDataHelper::generateMemberDeposit($member_id, $start, $end);
//        $spreadsheet = DownloadReport::downloadMemberDeposit($dataDepositMember,$member);
////        return $dataDepositMember;
//        $filename = $member->nik_koperasi.'_simpanan.xlsx';
//        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
//        $path = \Storage::disk('deposit')->path($filename);
//        $writer->save($path);
//        if(\Storage::disk('deposit')->exists($filename)){
//            return response()->download($path, $filename)->deleteFileAfterSend(true);
//        }
//        return redirect()->back();
    }
}
