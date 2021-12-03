<?php

namespace App\Http\Controllers;

use App\Helpers\DownloadReport;
use App\Helpers\reverseDataHelper;
use App\Member;
use Illuminate\Http\Request;

class GenerateMemberReportAreaProyek extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('report.generate.member-area.new');
    }

    public function download(Request $request){
        $start = $request->get('start');
        $end = $request->get('end');
        $dataAreaProyekAnggota = reverseDataHelper::generateMemberAreaProyek($start, $end);
        $spreadsheet = DownloadReport::downloadMemberDeposit($dataAreaProyekAnggota);
//        return $dataAreaProyekAnggota;
        $filename = 'anggota_area_proyek.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $path = \Storage::disk('deposit')->path($filename);
        $writer->save($path);
        if(\Storage::disk('deposit')->exists($filename)){
            return response()->download($path, $filename)->deleteFileAfterSend(true);
        }
//        return $genReportDeposit;
    }
}
