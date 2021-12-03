<?php

namespace App\Http\Controllers;

use App\Helpers\DownloadReport;
use App\Helpers\reverseDataHelper;
use App\Member;
use Illuminate\Http\Request;

class GenerateReportPengelolaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $admins = reverseDataHelper::generatePengelola();
        return view('report.generate.pengelola.new', compact('admins'));
    }


    public function getPengelola(Request $request)
    {

        $admins = reverseDataHelper::generatePengelola();
        $output = '';
        foreach ($admins as $admin) {
            $output .= '<tr>' .
                '<td>' . $admin['region_name'] . '</td>' .
                '<td>' . $admin['jumlah_pengelola'] . '</td>' .
                '<td>' . $admin['jumlah_anggota'] . '</td>' .
                '<td>' . $admin['belum_anggota'] . '</td>' .
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
