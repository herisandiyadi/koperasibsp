<?php

namespace App\Http\Controllers;

use App\Helpers\DownloadReport;
use App\Helpers\reverseDataHelper;
use App\Member;
use Illuminate\Http\Request;

class GenerateMemberResign extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('report.generate.member-resign.new');
    }


    public function getMember(Request $request)
    {

        $start = $request->get('start');
        $end = $request->get('end');
        $memberResign = reverseDataHelper::generateMemberResign($start, $end);
        $output = '';
        foreach ($memberResign as $resign) {
            $output .= '<tr>' .
                '<td>' . $resign['nama'] . '</td>' .
                '<td>' . $resign['proyek'] . '</td>' .
                '<td>' . $resign['area'] . '</td>' .
                '<td>' . number_format($resign['pokok']) . '</td>' .
                '<td>' . number_format($resign['wajib']) . '</td>' .
                '<td>' . number_format($resign['sukarela']) . '</td>' .
                '<td>' . number_format($resign['shu']) . '</td>' .
                '<td>' . number_format($resign['lainnya']) . '</td>' .
                '<td>' . number_format($resign['total']) . '</td>' .
                '</tr>';

        }
        return Response($output);
    }

    public function download(Request $request){

        $start = $request->get('start');
        $end = $request->get('end');
        $memberResign = reverseDataHelper::generateMemberResign($start, $end);
//        return $memberResign;
        $spreadsheet = DownloadReport::downloadMemberResign($memberResign, $start, $end);
        $filename = 'anggota_resign.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $path = \Storage::disk('deposit')->path($filename);
        $writer->save($path);
        if(\Storage::disk('deposit')->exists($filename)){
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }
        return redirect()->back();
    }
}
