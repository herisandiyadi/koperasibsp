<?php
namespace App\Helpers;
use App\Exceptions\ChangeConnectionException;

use App\GeneralSetting;
use App\Member;
use App\Position;
use App\Region;
use App\Resign;
use App\TsDeposits;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use function Psy\sh;

class ResponseHelper
{
	/**
	 * Formatted Json Response to FrontEnd
	 * @param int $code
	 * @param $data
	 * @param String $message
	 * @param array $header
	 * @return \Illuminate\Http\JsonResponse
	 */
	public static function json($data ,int $code,  $message = '', $header = []){
	    return response()->json(['result'=>$data,'message'=>$message], $code, $header);
	}
}

class CsvToArray
{
    function csv_to_array($filename = '', $header)
    {
        $delimiter = ',';
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

}

class CsvCombineArray
{
    function csv_combine_to_array($data_row, $header)
    {
        $delimiter = ',';
		foreach($data_row as $row)
		{
			if(count($header) == count($row)){
				$data[] = array_combine($header, $row);
			}

		}
        return $data;
    }

}

class ReverseData
{
    public static function buildAdminData($admin){
        $admin->map(function($a){
            $region = Region::where('name_area', 'like', $a[1])->first();
            $position = Position::where('description', 'like', $a[5])->first();
            return [
                'user' => [
                    'email' => $a[3],
                    'password' => \Hash::make($a[4]),
                    'name' => $a[0],
                    'username' => $a[0],
                    'position' => $position['id']
                ],
                'member' => [
                    'email' => $a[3],
                    'first_name' => $a[0],
                    'region_id' => $region['id'],
                    'position_id' => $position['id'],
                    'is_active' => 1
                ]
            ];
        });
    }

    public static function genAlphabet($colPosition, $param, $value){
        if($param === '-'){
            return chr(ord(strtoupper($colPosition)) - $value);
        }
        return chr(ord(strtoupper($colPosition)) + $value);

    }

    public static function getNameFromNumber($num) {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return self::getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
}

class cutOff
{
    public static function getCutoff()
    {
        $carbon = now()->format('Y-m');
        $cutOff = GeneralSetting::where('name', 'cut-off')->first();
        $from = Carbon::parse($carbon.'-'.$cutOff->content);

        if($from->lte(now())){
            $from = Carbon::parse($carbon.'-'.$cutOff->content)->addMonth(1)->format('Y-m-d');
        }else{
            $from = Carbon::parse($carbon.'-'.$cutOff->content)->format('Y-m-d');
        }

        return $from;
    }

    public static function getPemotongan()
    {
        $carbon = now()->format('Y-m');
        $cutOff = GeneralSetting::where('name', 'generate-potongan')->first();
        $from = Carbon::parse($carbon.'-'.$cutOff->content);

        if($from->lte(now())){
            $from = Carbon::parse($carbon.'-'.$cutOff->content)->addMonth(1)->format('Y-m-d');
        }else{
            $from = Carbon::parse($carbon.'-'.$cutOff->content)->format('Y-m-d');
        }

        return $from;
    }

    public static function getBungaBerjalan($value, $bunga_berjalan, $tanggal_pengajuan){
        $carbon = now()->format('Y-m');
        $cutOff = GeneralSetting::where('name', 'generate-potongan')->first();
        $from = Carbon::parse($carbon.'-'.$cutOff->content);
        $tanggalPengajuan = Carbon::parse($tanggal_pengajuan);

        if($from->lte($tanggalPengajuan)){
            $cutOff = self::getCutoff();
            $diffDays = $tanggalPengajuan->diffInDays($cutOff);
        }else{
            $to = Carbon::parse($carbon.'-'.$cutOff->content);
            $diffDays = $to->diffInDays($from);
        }
        $bungaBerjalan = $value * ($bunga_berjalan/100);
        $bungaBerjalan = $diffDays * $bungaBerjalan;

        return $bungaBerjalan;
    }

    public static function getDayBungaBerjalan($tanggal_pengajuan){
        $carbon = now()->format('Y-m');
        $tglPotong = GeneralSetting::where('name', 'generate-potongan')->first();
        $from = Carbon::parse($carbon.'-'.$tglPotong->content);
        $tanggalPengajuan = Carbon::parse($tanggal_pengajuan);
//        dd($from->lte($tanggalPengajuan));
        if($from->lte($tanggalPengajuan)){
            $cutOff = self::getCutoff();
            $diffDays = $tanggalPengajuan->diffInDays($cutOff);
        }else{
            $to = Carbon::parse($carbon.'-'.$tglPotong->content);
            $diffDays = $to->diffInDays($from);
        }

        return $diffDays;
    }
}

class dateConvert
{
    public static function getAllMonth()
    {
        $firstMonth = now()->firstOfYear()->format('Y-m-d');
        $lastMonth = now()->lastOfYear()->format('Y-m-d');
        $months = [];
        foreach (CarbonPeriod::create($firstMonth, '1 month', $lastMonth) as $month) {
            $months[] = $month->format('F Y');
        }
        return collect($months);
    }
}

class grafikData
{
    public static function simpananYearly($start, $end, $region)
    {

        $months = [];
        $values = [];
        $value = 0;
        foreach (CarbonPeriod::create($start, '1 month', $end) as $month) {

            $simpanan = TsDeposits::getYearlyDeposit($month, $region);


            if(isset($simpanan->total)){
                $value = $simpanan->total;
            }
            $months[] = $month->format('F Y');
            $values[] = $value;
        }
        $data = [
            'bulan' => $months,
            'value' => $values
        ];
        return collect($data);
    }
}

class DownloadReport{

    public static function generateDeposit($start, $end)
    {
        $pokok = [];
        $wajib = [];
        $sukarela = [];

        $debitPokok = 0;
        $creditPokok = 0;

        $debitWajib = 0;
        $creditWajib = 0;

        $debitSukarela = 0;
        $creditSukarela = 0;

        foreach (CarbonPeriod::create($start, '1 month', $end) as $month) {

            $debitDataPokok = TsDeposits::getYearlyDepositType($month, 'debit', 1);
            $creditDataPokok = TsDeposits::getYearlyDepositType($month, 'credit', 1);

            $debitDataWajib = TsDeposits::getYearlyDepositType($month, 'debit', 2);
            $creditDataWajib = TsDeposits::getYearlyDepositType($month, 'credit', 2);

            $debitDataSukarela = TsDeposits::getYearlyDepositType($month, 'debit', 3);
            $creditDataSukarela = TsDeposits::getYearlyDepositType($month, 'credit', 3);

            if(isset($debitDataPokok->total)){
                $debitPokok = $debitDataPokok->total;
            }

            if(isset($creditDataPokok->total)){
                $creditPokok = $creditDataPokok->total;
            }

            if(isset($debitDataWajib->total)){
                $debitWajib = $debitDataWajib->total;
            }

            if(isset($creditDataWajib->total)){
                $creditWajib = $creditDataWajib->total;
            }

            if(isset($debitDataSukarela->total)){
                $debitSukarela = $debitDataSukarela->total;
            }

            if(isset($creditDataSukarela->total)){
                $creditSukarela = $creditDataSukarela->total;
            }

            $pokok[] = [
                'bulan' => $month->format('F Y'),
                'debit' => $debitPokok,
                'credit' => $creditPokok
            ];

            $wajib[] = [
                'bulan' => $month->format('F Y'),
                'debit' => $debitWajib,
                'credit' => $creditWajib
            ];

            $sukarela[] = [
                'bulan' => $month->format('F Y'),
                'debit' => $debitSukarela,
                'credit' => $creditSukarela
            ];
        }
        $data = [
            'pokok' => $pokok,
            'wajib' => $wajib,
            'sukarela' => $sukarela
        ];
        return collect($data);
    }

    public static function generateMember($start, $end){
        $start_join = Carbon::parse($start);
        $end_join = Carbon::parse($end);
        $member = Member::with('position', 'region')
            ->whereBetween('join_date', [$start_join, $end_join])
            ->get();
        return $member;
    }

    public static function generateRekapAnggota($start, $end){
        $start_join = Carbon::parse($start)->format('Y-m-d');
        $end_join = Carbon::parse($end)->format('Y-m-d');
        $months = self::generateMonth($start_join, $end_join);
        $regions = Region::get();

        $data = [];
        foreach ($regions as $region){
            foreach ($months as $month){
                $member = Member::where('region_id', $region['id'])
                    ->whereMonth('join_date','>=', $month->format('m'))
                    ->whereMonth('join_date','<=',$month->format('m'))
                    ->count();
                $data[] = [
                    'region' => $region['name_area'],
                    'total' => $member,
                    'bulan' =>  $month->format('F Y')
                ];
            }
        }

        $arr = [];

        foreach ($data as $key => $item) {
            $arr[$item['bulan']][$key] = $item;
        }


        $a = collect($arr)->map(function ($q){
           return array_values($q);
        });

//        foreach ($months as $month){
//            foreach ($regions as $region){
//                $member = Member::where('region_id', $region['id'])->whereMonth('join_date',$month->format('m'))->count();
//                $data[] = [
//                    'region' => $region['name_area'],
//                    'total' => $member,
//                    'bulan' =>  $month->format('F Y')
//                ];
//            }
//        }
        return $a->toArray();
    }

    public static function generateMonth($start,$end ){
        $months = [];
        foreach (CarbonPeriod::create($start, '1 month', $end) as $month) {
            $months[] = $month;
        }
        return $months;
    }

    public static function downloadMemberDeposit($data, $member){
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5c842',
                ]
            ],
        ];
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(0)->setTitle('Data Simpanan');
        $sheet->getCell('A1')->setValue('KARTU PINJAMAN KOPERASI SECURITY "BSP"');
        $sheet->getCell('A3')->setValue('Nama Anggota');
        $sheet->getCell('A4')->setValue('Lokasi Proyek');
        $sheet->getCell('A5')->setValue('No. Anggota KS"BSP"');

        $sheet->getCell('B3')->setValue($member->full_name);
        $sheet->getCell('B4')->setValue($member->project->project_name);
        $sheet->getCell('B5')->setValue($member->nik_koperasi);

        $sheet->getCell('A7')->setValue('Tahun');
        $sheet->getCell('B7')->setValue('Simpanan Pokok');
        $sheet->getCell('C7')->setValue('Simpanan Wajib');
        $sheet->getCell('D7')->setValue('Simpanan Sukarela');
        $sheet->getCell('E7')->setValue('SHU Ditahan');

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1:E1')->applyFromArray($styleArray);
        $sheet->getStyle('A7:E7')->applyFromArray($styleArray);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $b = 7;
        foreach ($data as $d){
            ++$b;
            $sheet->getCell('A'.$b)->setValue($d['tahun']);
            $sheet->getCell('B'.$b)->setValue($d['pokok']);
            $sheet->getCell('C'.$b)->setValue($d['wajib']);
            $sheet->getCell('D'.$b)->setValue($d['sukarela']);
            $sheet->getCell('E'.$b)->setValue($d['shu']);

            $sheet->getStyle('B'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('C'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('D'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('E'.$b)->getNumberFormat()->setFormatCode('#,##0');
        }
        $endCol = $b+1;
        $sheet->getCell('A'.$endCol)->setValue('TOTAL');
        $sheet->getCell('B'.$endCol)->setValue('=SUM(B8:B'.$b.')');
        $sheet->getCell('C'.$endCol)->setValue('=SUM(C8:C'.$b.')');
        $sheet->getCell('D'.$endCol)->setValue('=SUM(D8:D'.$b.')');
        $sheet->getCell('E'.$endCol)->setValue('=SUM(E8:E'.$b.')');
        $sheet->getStyle('B'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('C'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E'.$endCol)->getNumberFormat()->setFormatCode('#,##0');

        $sheet->getStyle('A'.$endCol.':E'.$endCol)->applyFromArray($styleArray);



        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;

    }

    public static function downloadMemberResign($data, $start, $end){
        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5c842',
                ]
            ],
        ];
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(0)->setTitle('Data Anggota Resign');
        $sheet->getCell('A1')->setValue('Laporan Anggota Resign');
        $sheet->getCell('A2')->setValue('Periode');
        $sheet->getCell('B2')->setValue($start_date .' s/d '. $end_date);

        $sheet->getCell('A4')->setValue('No.');
        $sheet->getCell('B4')->setValue('Nama Anggota');
        $sheet->getCell('C4')->setValue('Proyek');
        $sheet->getCell('D4')->setValue('Area Wilayah');
        $sheet->getCell('E4')->setValue('Simpanan');
        $sheet->getCell('E5')->setValue('Pokok');
        $sheet->getCell('F5')->setValue('Wajib');
        $sheet->getCell('G5')->setValue('Sukarela');
        $sheet->getCell('H5')->setValue('SHU Ditahan');
        $sheet->getCell('I5')->setValue('Lainnya');
        $sheet->getCell('J4')->setValue('Total');
        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->mergeCells('D4:D5');
        $sheet->mergeCells('E4:I4');
        $sheet->mergeCells('J4:J5');

        $sheet->getStyle('A4:J5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:J5')->applyFromArray($styleArray);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $no = 0;
        $b = 5;
        foreach ($data as $d){
            ++$b;

            $sheet->getCell('A'.$b)->setValue(++$no);
            $sheet->getCell('B'.$b)->setValue($d['nama']);
            $sheet->getCell('C'.$b)->setValue($d['proyek']);
            $sheet->getCell('D'.$b)->setValue($d['area']);
            $sheet->getCell('E'.$b)->setValue($d['pokok']);
            $sheet->getCell('F'.$b)->setValue($d['wajib']);
            $sheet->getCell('G'.$b)->setValue($d['sukarela']);
            $sheet->getCell('H'.$b)->setValue($d['shu']);
            $sheet->getCell('I'.$b)->setValue($d['lainnya']);
            $sheet->getCell('J'.$b)->setValue($d['total']);

            $sheet->getStyle('E'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('F'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('H'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('I'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('J'.$b)->getNumberFormat()->setFormatCode('#,##0');
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;

    }

}

class reverseDataHelper{
    public static function generateMemberDeposit($member_id, $start, $end){

        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');
        $months = DownloadReport::generateMonth($start_date, $end_date);
        $m =[];
        foreach ($months as $month){
            $memberDepositPokok = TsDeposits::totalDepositPokokDate($member_id, $month);
            $memberDepositWajib = TsDeposits::totalDepositWajibDate($member_id, $month);
            $memberDepositSukarela = TsDeposits::totalDepositSukarelaDate($member_id, $month);
            $memberDepositShu = TsDeposits::totalDepositShuDate($member_id, $month);
            $m[] = [
                'tahun' => $month->format('F Y'),
                'pokok' => $memberDepositPokok,
                'wajib' => $memberDepositWajib,
                'sukarela' => $memberDepositSukarela,
                'shu' => $memberDepositShu
            ];
        }

        return $m;
    }

    public static function generateMemberDepositSukarela($member_id, $start, $end){

        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');
        $months = DownloadReport::generateMonth($start_date, $end_date);
        $m =[];
        foreach ($months as $month){
            $memberDepositSukarela = TsDeposits::sukarelaMemberSearch($member_id, $month);
            $saldo = $memberDepositSukarela['debit'] - $memberDepositSukarela['credit'];

            $m[] = [
                'tahun' => $month->format('F Y'),
                'masuk' => $memberDepositSukarela['debit'],
                'keluar' =>$memberDepositSukarela['credit'],
                'saldo' => $saldo,
            ];
        }

        return $m;
    }

    public static function generateMemberAreaProyek($start, $end){

        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');
        $months = DownloadReport::generateMonth($start_date, $end_date);
        $regions = Region::all();
        $m =[];

        foreach ($regions as $region){
            $m[] = [
              'region' => $region->name_area,
                'project' => ''
            ];
        }

        return $m;
    }

    public static function generateMemberResign($start, $end){
        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');

        $memberResign = Resign::whereDate('date','>=', $start_date)
            ->whereDate('date','<=', $end_date)
            ->get();
        $m = [];

        foreach ($memberResign as $resign){
            if(!isset($resign->member->region->name_area))
            {
                $nameArea = "";
            }else{
                $nameArea = $resign->member->region->name_area;
            }
            $pokok = TsDeposits::totalDepositPokok($resign->member->id);
            $sukarela = TsDeposits::totalDepositSukarela($resign->member->id);
            $wajib = TsDeposits::totalDepositWajib($resign->member->id);
            $shu = TsDeposits::totalDepositShu($resign->member->id);
            $lainnya = TsDeposits::totalDepositLainnya($resign->member->id);
            $total = $pokok + $sukarela + $wajib + $shu + $lainnya;
            $m[] = [
              'nama' => $resign->member->full_name,
              'proyek' => $resign->member->project->project_name,
              'area' => $nameArea,
                'pokok' => $pokok,
                'sukarela' => $sukarela,
                'wajib' => $wajib,
                'shu' => $shu,
                'lainnya' => $lainnya,
                'total' => $total
            ];
        }
        return $m;
    }

    public static function downloadDeposit($simpanan, $genDeposit)
    {

        $maks = 100;
        $b = 1;

        //style header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5c842',
                ]
            ],
        ];
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $dataSimpananSheet = $spreadsheet->getActiveSheet(0)->setTitle('Simpanan');

        // apply style to header
        $dataSimpananSheet->getStyle('A5:G6')->applyFromArray($styleArray);

        // set auto width
        $dataSimpananSheet->getColumnDimension('A')->setWidth(30);
        $dataSimpananSheet->getColumnDimension('B')->setWidth(20);
        $dataSimpananSheet->getColumnDimension('C')->setWidth(20);
        $dataSimpananSheet->getColumnDimension('D')->setWidth(15);
        $dataSimpananSheet->getColumnDimension('E')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('F')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('G')->setWidth(20);

        // set header
        $dataSimpananSheet->getCell('A1')->setValue('Tabel Posisi Keuangan Simpanan Pokok, Wajib dan Sukarela');
        $dataSimpananSheet->getCell('A2')->setValue('Period Start :');
        $dataSimpananSheet->getCell('A3')->setValue('Period End :');
        $dataSimpananSheet->getCell('B2')->setValue($genDeposit->start);
        $dataSimpananSheet->getCell('B3')->setValue($genDeposit->end);

        $dataSimpananSheet->mergeCells('B5:C5');
        $dataSimpananSheet->mergeCells('D5:E5');
        $dataSimpananSheet->mergeCells('F5:G5');
        $dataSimpananSheet->mergeCells('A5:A6');


        $dataSimpananSheet->getCell('A5')->setValue('Period');
        $dataSimpananSheet->getCell('B5')->setValue('Simpanan Pokok');
        $dataSimpananSheet->getCell('B6')->setValue('Saldo Masuk');
        $dataSimpananSheet->getCell('C6')->setValue('Saldo Keluar');

        $dataSimpananSheet->getCell('D5')->setValue('Simpanan Wajib');
        $dataSimpananSheet->getCell('D6')->setValue('Saldo Masuk');
        $dataSimpananSheet->getCell('E6')->setValue('Saldo Keluar');

        $dataSimpananSheet->getCell('F5')->setValue('Simpanan Sukarela');
        $dataSimpananSheet->getCell('F6')->setValue('Saldo Masuk');
        $dataSimpananSheet->getCell('G6')->setValue('Saldo Keluar');

        $b = 6;
        $simpananPokok = $simpanan['pokok'];
        foreach ($simpananPokok as $pokok){
            ++$b;
            $dataSimpananSheet->getCell('A'.$b)->setValue($pokok['bulan']);
            $dataSimpananSheet->getCell('B'.$b)->setValue($pokok['debit']);
            $dataSimpananSheet->getCell('C'.$b)->setValue($pokok['credit']);
            $dataSimpananSheet->getStyle('B'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $dataSimpananSheet->getStyle('C'.$b)->getNumberFormat()->setFormatCode('#,##0');
        }
        $endCol = $b + 1;
        $dataSimpananSheet->getCell('A'.$endCol)->setValue('TOTAL');
        $dataSimpananSheet->getCell('B'.$endCol)->setValue('=SUM(B7:B'.$b.')');
        $dataSimpananSheet->getCell('C'.$endCol)->setValue('=SUM(C7:C'.$b.')');
        $dataSimpananSheet->getStyle('B'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $dataSimpananSheet->getStyle('C'.$endCol)->getNumberFormat()->setFormatCode('#,##0');

        $b = 6;
        $simpananWajib = $simpanan['wajib'];
        foreach ($simpananWajib as $wajib){
            ++$b;
            $dataSimpananSheet->getCell('D'.$b)->setValue($wajib['debit']);
            $dataSimpananSheet->getCell('E'.$b)->setValue($wajib['credit']);
            $dataSimpananSheet->getStyle('D'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $dataSimpananSheet->getStyle('E'.$b)->getNumberFormat()->setFormatCode('#,##0');
        }
        $endCol = $b + 1;
        $dataSimpananSheet->getCell('D'.$endCol)->setValue('=SUM(D7:D'.$b.')');
        $dataSimpananSheet->getCell('E'.$endCol)->setValue('=SUM(E7:E'.$b.')');
        $dataSimpananSheet->getStyle('D'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $dataSimpananSheet->getStyle('E'.$endCol)->getNumberFormat()->setFormatCode('#,##0');

        $b = 6;
        $simpananSukarela = $simpanan['sukarela'];
        foreach ($simpananSukarela as $sukarela){
            ++$b;
            $dataSimpananSheet->getCell('F'.$b)->setValue($sukarela['debit']);
            $dataSimpananSheet->getCell('G'.$b)->setValue($sukarela['credit']);
            $dataSimpananSheet->getStyle('F'.$b)->getNumberFormat()->setFormatCode('#,##0');
            $dataSimpananSheet->getStyle('G'.$b)->getNumberFormat()->setFormatCode('#,##0');
        }
        $endCol = $b + 1;
        $dataSimpananSheet->getCell('F'.$endCol)->setValue('=SUM(F7:F'.$b.')');
        $dataSimpananSheet->getCell('G'.$endCol)->setValue('=SUM(G7:G'.$b.')');
        $dataSimpananSheet->getStyle('F'.$endCol)->getNumberFormat()->setFormatCode('#,##0');
        $dataSimpananSheet->getStyle('G'.$endCol)->getNumberFormat()->setFormatCode('#,##0');

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    public static function downloadMember($members, $genMember)
    {

        $maks = 100;
        $b = 1;

        //style header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5c842',
                ]
            ],
        ];
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $dataSimpananSheet = $spreadsheet->getActiveSheet(0)->setTitle('Anggota');

        // apply style to header
        $dataSimpananSheet->getStyle('A5:I5')->applyFromArray($styleArray);

        // set auto width
        $dataSimpananSheet->getColumnDimension('A')->setWidth(30);
        $dataSimpananSheet->getColumnDimension('B')->setWidth(30);
        $dataSimpananSheet->getColumnDimension('C')->setWidth(20);
        $dataSimpananSheet->getColumnDimension('D')->setWidth(15);
        $dataSimpananSheet->getColumnDimension('E')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('F')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('G')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('H')->setAutoSize(true);
        $dataSimpananSheet->getColumnDimension('I')->setAutoSize(true);

        // set header
        $dataSimpananSheet->getCell('A1')->setValue('Laporan Pendaftaran Karyawan Koperasi BSP');
        $dataSimpananSheet->getCell('A2')->setValue('Period Start :');
        $dataSimpananSheet->getCell('A3')->setValue('Period End :');
        $dataSimpananSheet->getCell('B2')->setValue($genMember->start);
        $dataSimpananSheet->getCell('B3')->setValue($genMember->end);


        $dataSimpananSheet->getCell('A5')->setValue('NIK Koperasi');
        $dataSimpananSheet->getCell('B5')->setValue('NIK Koperasi Lama');
        $dataSimpananSheet->getCell('B5')->setValue('NIK BSP');
        $dataSimpananSheet->getCell('D5')->setValue('Nama Anggota');
        $dataSimpananSheet->getCell('E5')->setValue('Area');
        $dataSimpananSheet->getCell('F5')->setValue('Tanggal Bergabung');
        $dataSimpananSheet->getCell('G5')->setValue('Email');
        $dataSimpananSheet->getCell('H5')->setValue('Alamat');
        $dataSimpananSheet->getCell('I5')->setValue('No Handphone');

        $b = 5;
        foreach ($members as $member){
            ++$b;
            if(!isset($member->region['name_area']))
            {
                $namearea = 'not-set';
            }else{
                $namearea = $member->region['name_area'];
            }
            $dataSimpananSheet->getCell('A'.$b)->setValue($member['nik_koperasi']);
            $dataSimpananSheet->getCell('B'.$b)->setValue($member['nik_koperasi_lama']);
            $dataSimpananSheet->getCell('C'.$b)->setValue($member['nik_bsp']);
            $dataSimpananSheet->getCell('D'.$b)->setValue($member['first_name']);
            $dataSimpananSheet->getCell('E'.$b)->setValue($namearea);
            $dataSimpananSheet->getCell('F'.$b)->setValue($member['join_date']->format('Y-m-d'));
            $dataSimpananSheet->getCell('G'.$b)->setValue($member['email']);
            $dataSimpananSheet->getCell('H'.$b)->setValue($member['address']);
            $dataSimpananSheet->getCell('I'.$b)->setValue($member['phone_number']);
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    public static function downloadRekapMember($dataMember, $genRekapMember)
    {

        $maks = 100;
        $b = 1;

        //style header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5c842',
                ]
            ],
        ];

        $styleArrayKeluar = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'f5ae9a',
                ]
            ],
        ];

        $styleArrayMasuk = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'c5e0d2',
                ]
            ],
        ];

        $styleArrayTotal = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'c7c7c7',
                ]
            ],
        ];

        $styleTitleArray = array(
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => '30453a'),
                'size'  => 15,
            ));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $dataSimpananSheet = $spreadsheet->getActiveSheet(0)->setTitle('Rekap Anggota');
        $dataSimpananSheet->getCell('A1')->setValue('Laporan Rekap Anggota Koperasi BSP');
        $dataSimpananSheet->getCell('A2')->setValue('Period Start :');
        $dataSimpananSheet->getCell('A3')->setValue('Period End :');
        $dataSimpananSheet->getCell('B2')->setValue($genRekapMember->start);
        $dataSimpananSheet->getCell('B3')->setValue($genRekapMember->end);

        $dataSimpananSheet->mergeCells('A5:A7');
        $dataSimpananSheet->getCell('A5')->setValue('Area Wilayah');
        $dataSimpananSheet->getCell('B5')->setValue('Bulan');
        $dataSimpananSheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $dataSimpananSheet->getColumnDimension('A')->setAutoSize(true);

        $dataSimpananSheet->getStyle('A1')->applyFromArray($styleTitleArray);


            $iArea = 8;
            $colArea = 'A';
            $area = Region::get();
            foreach($area as $value) {
                $dataSimpananSheet->setCellValue($colArea.$iArea++, $value['name_area']);
            }
            $startDataArea = $colArea.'8';
            $endDataArea = $colArea.$iArea++;
            $dataSimpananSheet->getStyle($startDataArea.':'.$endDataArea)->applyFromArray($styleArray);

            $iMonth = 6;
            $colMonth = 'B';
            $colMonthMerge = 'C';
            $iInfo = 7;
            $months = DownloadReport::generateMonth($genRekapMember->start, $genRekapMember->end);

            $totalColMonth = count($months) * 2;

        $ranges = [];
            foreach($months as $value){
                $dataSimpananSheet->setCellValue($colMonth.$iMonth, Carbon::parse($value)->format('F Y'));
                $dataSimpananSheet->getStyle($colMonth.$iMonth)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $dataSimpananSheet->setCellValue($colMonth.$iInfo, 'Masuk');
                $dataSimpananSheet->mergeCells($colMonth.$iMonth.':'.$colMonthMerge.$iMonth);

                $ranges[]=$colMonth;

                $colMonth++;
                $colMonthMerge++;
                $dataSimpananSheet->setCellValue($colMonth.$iInfo, 'Keluar');

                $ranges[]=$colMonth;

                $colMonth++;
                $colMonthMerge++;
//                $dataSimpananSheet->mergeCells($colMonth.$iMonth.':'.$colMonthMerge.$iMonth);
            }
        $startSumTotal = $iInfo+1;
        $endSumTotal = $iInfo+count($area);
        $dataSimpananSheet->setCellValue('A'.($endSumTotal+1), 'TOTAL');
        foreach ($ranges as $range){
            $endRangeSumTotal = $endSumTotal+1;
            $dataSimpananSheet->setCellValue($range.$endRangeSumTotal, '=SUM('.$range.$startSumTotal.':'.$range.$endSumTotal.')');
            $dataSimpananSheet->getStyle($range.$endRangeSumTotal)->applyFromArray($styleArrayTotal);
        }

        $dataSimpananSheet->mergeCells('B5:'.ReverseData::getNameFromNumber($totalColMonth).'5');
        $dataSimpananSheet->getStyle('B5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//        $dataSimpananSheet->mergeCells(ReverseData::getNameFromNumber($totalColMonth+1).'5:'.ReverseData::getNameFromNumber($totalColMonth+1).'7');
//        $dataSimpananSheet->setCellValue(ReverseData::getNameFromNumber($totalColMonth+1).'5', 'TOTAL');
        $dataSimpananSheet->getStyle('A5:'.ReverseData::getNameFromNumber($totalColMonth).'7')->applyFromArray($styleArray);
//        dd(ReverseData::getNameFromNumber($totalColMonth));
        $anggotaMasuk = [];
            foreach ($area as $region){
                foreach ($months as $month){
                    $member = Member::where('region_id', $region['id'])
                        ->whereMonth('join_date','>=', $month->format('m'))
                        ->whereYear('join_date','>=', $month->format('Y'))
                        ->whereMonth('join_date','<=',$month->format('m'))
                        ->whereYear('join_date','<=',$month->format('Y'))
                        ->where('is_active', 1)
                        ->count();
                    $anggotaMasuk[] =  $member;
                }
            }
            $countMonth = count($months);
            $iAnggota = 8;
            $colAnggota = 'B';
            $resetCountMonth = 0;
            foreach($anggotaMasuk as $value){

                if($resetCountMonth === $countMonth){
                    $resetCountMonth = 0;
                    $iAnggota++;
                    $colAnggota = 'B';
                }

                $dataSimpananSheet->setCellValue($colAnggota.$iAnggota, $value);
                $dataSimpananSheet->getStyle($colAnggota.$iAnggota)->applyFromArray($styleArrayMasuk);

                $resetCountMonth++;
                $colAnggota++;
                $colAnggota++;
            }

        $anggotaKeluar = [];
        foreach ($area as $region){
            foreach ($months as $month){
                $member = Member::where('region_id', $region['id'])
                    ->whereMonth('join_date','>=', $month->format('m'))
                    ->whereYear('join_date','>=', $month->format('Y'))
                    ->whereMonth('join_date','<=',$month->format('m'))
                    ->whereYear('join_date','<=',$month->format('Y'))
                    ->where('is_active', 0)
                    ->count();
                $anggotaKeluar[] =  $member;
            }
        }

        $countMonthKeluar = count($months);
        $iAnggotaKeluar = 8;
        $colAnggotaKeluar = 'C';
        $resetCountMonthKeluar = 0;
        foreach($anggotaKeluar as $value){
            if($resetCountMonthKeluar === $countMonthKeluar){
                $resetCountMonthKeluar = 0;
                $iAnggotaKeluar++;
                $colAnggotaKeluar = 'C';
            }

            $dataSimpananSheet->setCellValue($colAnggotaKeluar.$iAnggotaKeluar, $value);
            $dataSimpananSheet->getStyle($colAnggotaKeluar.$iAnggotaKeluar)->applyFromArray($styleArrayKeluar);
            $resetCountMonthKeluar++;
            $colAnggotaKeluar++;
            $colAnggotaKeluar++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    public static function generatePengelola(){
        $regions = Region::with('member.user')->get();
        $m = [];

        foreach ($regions as $region){
            $m[] = [
                'region_name' => $region->name_area,
                'jumlah_anggota' => count($region->member),
                'jumlah_pengelola' =>  count($region->member),
                'belum_anggota' => 0,
            ];
        }

        return $m;
    }

    public static function generateDataPayrol($start, $end){

        $start_date = Carbon::parse($start)->format('Y-m-d');
        $end_date = Carbon::parse($end)->format('Y-m-d');

        $months = DownloadReport::generateMonth($start_date, $end_date);
        $m =[];
        foreach ($months as $month){
            $memberDepositPokok = TsDeposits::totalDepositPokokDate($member_id, $month);
            $memberDepositWajib = TsDeposits::totalDepositWajibDate($member_id, $month);
            $memberDepositSukarela = TsDeposits::totalDepositSukarelaDate($member_id, $month);
            $memberDepositShu = TsDeposits::totalDepositShuDate($member_id, $month);
            $m[] = [
                'tahun' => $month->format('F Y'),
                'pokok' => $memberDepositPokok,
                'wajib' => $memberDepositWajib,
                'sukarela' => $memberDepositSukarela,
                'shu' => $memberDepositShu
            ];
        }

        return $m;
    }
}
