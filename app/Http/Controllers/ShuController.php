<?php

namespace App\Http\Controllers;

use App\Article;
use App\Member;
use App\Shu;
use App\TsDeposits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NotificationChannels\OneSignal\OneSignalChannel;
use Excel;
use Storage;
use PHPExcel;
class ShuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$shu = Shu::get();
		return view('shu.shu-list', compact('shu'));
    }

	public function datatable($shu)
	{
		if($shu == 'all')
		{
			$shu = Shu::all();
		}
		return \DataTables::of($shu)
			->editColumn('year', function($shuSetting){
				return $shuSetting->shu_year;
			})
			->editColumn('cadangan_yhd', function($shuSetting){
				return $shuSetting->cadangan_yhd;
			})
			->editColumn('shu', function($shuSetting){
				return number_format($shuSetting->shu);
			})
			->editColumn('is_complete', function($shuSetting){
				if($shuSetting->isComplete())
				{
					return 'Complete';
				}
				return 'Not Complete';
			})
			->addColumn('action', function($shuSetting){
				$deleteUrl = url('shu').'/'.$shuSetting->id;
				$editUrl = url('shu').'/'.$shuSetting->id.'/edit';
				$completeShu = url('shu').'/'.$shuSetting->id.'/complete';
				$vieShu = url('shu').'/'.$shuSetting->id.'/publish/1';
				$downloadShu = url('shu').'/download/'.$shuSetting->id.'';
				$btnEdit = '';
				$btnDelete = '';
				$btnDownload = '<a class="btn btn-sm btn-primary" href="'.$downloadShu.'" data-toggle="tooltip" title="Download"><i class="fa fa-file"></i></a>';
				$btnComplete = '<a class="btn btn-sm btn-primary" href="'.$completeShu.'" data-toggle="tooltip" title="Terbitkan"><i class="fa fa-check-circle"></i></a>';
				$btnView = '<a class="btn btn-sm btn-warning" href="'.$vieShu.'" data-toggle="tooltip" title="Terbit dan Sebarkan"><i class="fa fa-eye"></i></a>';
				if(auth()->user()->can('edit.master.shu')){
					$btnEdit = '<a class="btn btn-sm btn-primary" href="'.$editUrl.'"><i class="fa fa-edit"></i></a>';
				}
				if(auth()->user()->can('delete.master.shu')){
					$btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'shu'".','."'".$shuSetting->id."'".','."'". csrf_token() ."'".','."'stShu'".')"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button>';
//                    $btnDelete = '<form method="POST" action="'.$deleteUrl.'" class="form-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> </form>';
				}
				if($shuSetting->isComplete())
				{
					return $btnView .' '. $btnDownload;
				}
				return $btnEdit.' '.$btnDelete.' '.$btnComplete.' '.$btnView .' '. $btnDownload;

			})->make(true);
	}

	public function complete(Request $request, $id)
	{
		$shu = Shu::findOrFail($id);
		$shu->update(['is_complete'=> true]);

		session()->flash('success', 'Shu '.$shu->shu_year.' berhasil complete!');
		return redirect()->back();
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return view('shu.shu-create');

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

		$shu = Shu::where('shu_year', $request->get('shu_year'));
		if($shu->count() > 0){
			session()->flash('failed', trans('response-message.failed.create',['object'=>'Shu']));
			return redirect('shu');
		}
//		return $shu;
		$shu = new Shu;
		$shu->shu_year = $request->get('shu_year');
		$shu->shu = $request->get('shu');
		$shu->cadangan_yhd = $request->get('cadangan_yhd');
		$shu->pendidikan = $request->get('pendidikan');
		$shu->sosial = $request->get('sosial');
		$shu->pendiri = $request->get('pendiri');
		$shu->pengurus_pengawas = $request->get('pengurus_pengawas');
		$shu->pengurus = $request->get('pengurus');
		$shu->ketua1 = $request->get('ketua1');
		$shu->sekretaris = $request->get('sekretaris');
		$shu->bendahara = $request->get('bendahara');
		$shu->pengawas = $request->get('pengawas');
		$shu->ketua2 = $request->get('ketua2');
		$shu->pengawas1 = $request->get('pengawas1');
		$shu->pengawas2 = $request->get('pengawas2');
		$shu->anggota = $request->get('anggota');
		$shu->keanggotaan = $request->get('keanggotaan');
		$shu->s_wajib = $request->get('s_wajib');
		$shu->s_lainnya = $request->get('s_lainnya');
		$shu->s_sukarela = $request->get('s_sukarela');
		$shu->jasa_pinjaman = $request->get('jasa_pinjaman');
		$shu->retail = $request->get('retail');
		$shu->senioritas = $request->get('senioritas');
		$shu->total_anggota = $request->get('total_anggota');

		if($request->complete)
		{
			$shu->is_complete = 1;
		}else{
			$shu->is_complete = 0;
		}
		$shu->save();

		session()->flash('success', trans('response-message.success.create',['object'=>'Shu']));
		return redirect('shu');
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
		$shu = Shu::findOrFail($id);

		if (Shu::destroy($id)) {
			$data = 'Success';
		}else{
			$data = 'Failed';
		}
		session()->flash('success', trans('response-message.success.delete',['object'=>'Shu']));
		return redirect('shu');
    }

    public function download($id){
    	$shu = Shu::where('id',$id)->first();
		$path = Storage::disk('template')->getDriver()->getAdapter()->getPathPrefix();
		$excel_path = Storage::disk('template')->getDriver()->getAdapter()->applyPathPrefix('shu_template.xlsx');

		$deposits = TsDeposits::where('member_id', 5)->get();
		$members = Member::FActive()->get();

		Excel::load($excel_path, function($reader) use ($shu, $deposits, $members){
			$sheet = $reader->sheet(0);
			$supervisor = $sheet->getStyle();
			$sheet->setCellValue('B2', $shu->shu_name);
			$sheet->setCellValue('C2', $shu->shu_year);
			$sheet->setCellValue('D3', $shu->shu);
			$sheet->setCellValue('D4', $shu->total_anggota);
			$sheet->setCellValue('D6', $shu->cadangan_yhd.'%');
			$sheet->setCellValue('D7', $shu->pendidikan.'%');
			$sheet->setCellValue('D8', $shu->sosial.'%');
			$sheet->setCellValue('D9', $shu->pendiri.'%');
			$sheet->setCellValue('D10', $shu->pengurus_pengawas.'%');
			$sheet->setCellValue('D11', $shu->pengurus.'%');
			$sheet->setCellValue('D12', $shu->ketua1.'%');
			$sheet->setCellValue('D13', $shu->sekretaris.'%');
			$sheet->setCellValue('D14', $shu->bendahara.'%');
			$sheet->setCellValue('D15', $shu->pengawas.'%');
			$sheet->setCellValue('D16', $shu->ketua2.'%');
			$sheet->setCellValue('D17', $shu->pengawas1.'%');
			$sheet->setCellValue('D18', $shu->pengawas2.'%');
			$sheet->setCellValue('D19', $shu->anggota.'%');
			$sheet->setCellValue('D20', $shu->keanggotaan.'%');
			$sheet->setCellValue('D21', $shu->s_wajib.'%');
			$sheet->setCellValue('D22', $shu->s_lainnya.'%');
			$sheet->setCellValue('D23', $shu->s_sukarela.'%');
			$sheet->setCellValue('D24', $shu->jasa_pinjaman.'%');
			$sheet->setCellValue('D25', $shu->retail.'%');
			$sheet->setCellValue('D26', $shu->senioritas.'%');

			$supervisor->getActiveSheet()->setSelectedCell("A1");
			$style = $supervisor->applyFromArray(
				[
					"fill"    => [ "color" => [ "rgb" => "FF0000" ] ]
				]);
			$sheet->getParent()->addCellXf($style);


			$sheetLastShu = $reader->sheet(1);
			$supervisor = $sheetLastShu->getStyle();

			$sheetLastShu->setCellValue('B2', $shu->last_shu_name);
			$sheetLastShu->setCellValue('C2', $shu->last_shu_year);
			$sheetLastShu->setCellValue('D3', $shu->last_shu);
			$sheetLastShu->setCellValue('D4', $shu->last_total_anggota);
			$sheetLastShu->setCellValue('D6', $shu->last_cadangan_partisipasi.'%');
			$sheetLastShu->setCellValue('D7', $shu->last_pendidikan.'%');
			$sheetLastShu->setCellValue('D8', $shu->last_sosial.'%');
			$sheetLastShu->setCellValue('D9', $shu->last_pendiri.'%');
			$sheetLastShu->setCellValue('D10', $shu->last_pengurus_pengawas.'%');
			$sheetLastShu->setCellValue('D11', $shu->last_pengurus.'%');
			$sheetLastShu->setCellValue('D12', $shu->last_ketua1.'%');
			$sheetLastShu->setCellValue('D13', $shu->last_sekretaris.'%');
			$sheetLastShu->setCellValue('D14', $shu->last_bendahara.'%');
			$sheetLastShu->setCellValue('D15', $shu->last_pengawas.'%');
			$sheetLastShu->setCellValue('D16', $shu->last_ketua2.'%');
			$sheetLastShu->setCellValue('D17', $shu->last_pengawas1.'%');
			$sheetLastShu->setCellValue('D18', $shu->last_pengawas2.'%');
			$sheetLastShu->setCellValue('D19', $shu->last_anggota.'%');
			$sheetLastShu->setCellValue('D20', $shu->last_keanggotaan.'%');
			$sheetLastShu->setCellValue('D21', $shu->last_s_wajib.'%');
			$sheetLastShu->setCellValue('D22', $shu->last_s_lainnya.'%');
			$sheetLastShu->setCellValue('D23', $shu->last_s_sukarela.'%');
			$sheetLastShu->setCellValue('D24', $shu->last_jasa_pinjaman.'%');
			$sheetLastShu->setCellValue('D25', $shu->last_retail.'%');
			$sheetLastShu->setCellValue('D26', $shu->last_senioritas.'%');

			$supervisor->getActiveSheet()->setSelectedCell("A1");
			$style = $supervisor->applyFromArray(
				[
					"fill"    => [ "color" => [ "rgb" => "FF0000" ] ]
				]);
			$sheetLastShu->getParent()->addCellXf($style);

			$sheetSimpanan = $reader->sheet(2);
			$sheetKGT = $reader->sheet(3);
			$sheetWajib = $reader->sheet(4);
			$sheetSukarela = $reader->sheet(5);
			$sheetLainnya = $reader->sheet(6);
			$sheetJasa = $reader->sheet(7);


			$row = 4;
			$otherRow = 4;
			$sheet4Row = 6;
			$no = 0;

			foreach ( $members as $member )
			{
				$row = $row + 1;
				$otherRow = $otherRow + 1;
				$sheet4Row = $sheet4Row + 1;

				$no = $no + 1;

				$to = now();
				$from = $member->join_date;
				$jBulan = $to->diffInMonths($from);
				$swajib = collect($member->depositWajib);
				$sukarela = collect($member->depositSukarela);
				$slain = collect($member->depositLainlain);
				if(isset($member->pokok->value)){
					$pokok = $member->pokok->value;
				}else{
					$pokok = 0;
				}

				$jasapinjaman = collect($member->ts_loans);
				$totaljasa = $jasapinjaman->sum('rate_of_interest') / 100;
				$totalpinjaman = $jasapinjaman->sum('value') * $totaljasa;

				$snrts = $jBulan - 12;
				if($snrts <= 0)
				{
					$status = 'B';
				}else{
					$status = 'L';
				}
				$sheetSimpanan->setCellValue('B'.$row, $no);

				$sheetSimpanan->setCellValue('C'.$row, $member->status);
				$sheetSimpanan->setCellValue('E'.$row, $member->project->project_name);
				$sheetSimpanan->setCellValue('F'.$row, $member->fullname);
				$sheetSimpanan->setCellValue('G'.$row, $member->nik_koperasi);
				$sheetSimpanan->setCellValue('H'.$row, $jBulan);
				$sheetSimpanan->setCellValue('I'.$otherRow, '=H'.$otherRow.'*Sheet1!C26');
				$sheetSimpanan->setCellValue('L'.$row, $pokok);
				$sheetSimpanan->setCellValue('M'.$row, $swajib->sum('total'));
				$sheetSimpanan->setCellValue('N'.$row, $sukarela->sum('total'));
				$sheetSimpanan->setCellValue('O'.$row, $slain->sum('total'));
				$sheetSimpanan->setCellValue('P'.$row, $slain->sum('total')+$sukarela->sum('total')+$swajib->sum('total'));
				$sheetSimpanan->setCellValue('Q'.$row, $totalpinjaman);

				$sheetSimpanan->setCellValue('R'.$otherRow, '=Sheet4!G'.$sheet4Row);
				$sheetSimpanan->setCellValue('T'.$otherRow, '=Sheet4!H'.$sheet4Row);
				$sheetSimpanan->setCellValue('V'.$otherRow, '=Sheet4!F'.$sheet4Row);
				$sheetSimpanan->setCellValue('AA'.$otherRow, '=Sheet1!C20');
				$sheetSimpanan->setCellValue('AB'.$otherRow, '=Sheet1!D4');
				$sheetSimpanan->setCellValue('AC'.$otherRow, '=Sheet4!L'.$sheet4Row);
				$sheetSimpanan->setCellValue('AE'.$otherRow, '=Sheet4!J'.$sheet4Row);
				$sheetSimpanan->setCellValue('AG'.$otherRow, '=Sheet5!I'.$sheet4Row);
				$sheetSimpanan->setCellValue('AI'.$otherRow, '=Sheet6!AH'.$sheet4Row);
				$sheetSimpanan->setCellValue('AK'.$otherRow, '=Sheet7!I'.$sheet4Row);
				$sheetSimpanan->setCellValue('AM'.$otherRow, '=Sheet8!J'.$sheet4Row);
				$sheetSimpanan->setCellValue('AO'.$otherRow, '=+V'.$otherRow.'+AC'.$otherRow.'+AE'.$otherRow.'+AG'.$otherRow.'+AI'.$otherRow.'+AK'.$otherRow.'+AM'.$otherRow);
				$sheetSimpanan->setCellValue('AP'.$otherRow, '=+W'.$otherRow.'+AD'.$otherRow.'+AF'.$otherRow.'+AH'.$otherRow.'+AJ'.$otherRow.'+AL'.$otherRow.'+AN'.$otherRow);
				$sheetSimpanan->setCellValue('AQ'.$otherRow, '=SUM(AO'.$otherRow.':AP'.$otherRow.')');
				$sheetSimpanan->setCellValue('AR'.$otherRow, '=AO'.$otherRow.'*AR4');
				$sheetSimpanan->setCellValue('AS'.$otherRow, '=(AQ'.$otherRow.'-AR'.$otherRow.')+SUM(R'.$otherRow.':U'.$otherRow.')');
				$sheetSimpanan->setCellValue('AT'.$otherRow, '=B'.$otherRow);
				$sheetSimpanan->setCellValue('AU'.$otherRow, '=Sheet1!D4');
				$sheetSimpanan->setCellValue('BA'.$otherRow, '=+AD'.$otherRow.'+AF'.$otherRow.'+AH'.$otherRow.'+AJ'.$otherRow.'+AL'.$otherRow.'+AN'.$otherRow);
				$sheetSimpanan->setCellValue('BB'.$otherRow, '=Sheet1!D3');
				$sheetSimpanan->setCellValue('BC'.$otherRow, '=Sheet1!C32+Sheet1!C33+Sheet1!C34');
				$sheetSimpanan->setCellValue('BD'.$otherRow, '=Sheet1!C21');
				$sheetSimpanan->setCellValue('BE'.$otherRow, '=Sheet1!C23');
				$sheetSimpanan->setCellValue('BF'.$otherRow, '=Sheet1!C22');
				$sheetSimpanan->setCellValue('BG'.$otherRow, '=Sheet1!C24');
				$sheetSimpanan->setCellValue('BH'.$otherRow, '=Sheet1!C26');
				$sheetSimpanan->setCellValue('BH'.$otherRow, '=Sheet1!C26');
				$sheetSimpanan->setCellValue('BI'.$otherRow, '=Sheet1!D3');
				$sheetSimpanan->setCellValue('BJ'.$otherRow, '=+AC'.$otherRow.'+AG'.$otherRow.'+AI'.$otherRow.'+AK'.$otherRow.'+AM'.$otherRow.'+I'.$otherRow);
				$sheetSimpanan->setCellValue('BK'.$otherRow, '=BJ'.$otherRow.'+BA'.$otherRow);
				$sheetSimpanan->setCellValue('BL'.$otherRow, '=BJ'.$otherRow.'*10%');
				$sheetSimpanan->setCellValue('BM'.$otherRow, '=BK'.$otherRow.'-BL'.$otherRow);
				$sheetSimpanan->setCellValue('BN'.$otherRow, '=M'.$otherRow);
				$sheetSimpanan->setCellValue('BO'.$otherRow, '=N'.$otherRow);
				$sheetSimpanan->setCellValue('BP'.$otherRow, '=O'.$otherRow);
				$sheetSimpanan->setCellValue('BQ'.$otherRow, '=Q'.$otherRow);
				$sheetSimpanan->setCellValue('BR'.$otherRow, '=H'.$otherRow);
				$sheetSimpanan->setCellValue('BS'.$otherRow, '=Sheet1!C9');
				$sheetSimpanan->setCellValue('BU'.$otherRow, '=V'.$otherRow.'+W'.$otherRow.'+Y'.$otherRow);
				$sheetSimpanan->setCellValue('BX'.$otherRow, '=R'.$otherRow.'+S'.$otherRow);
				$sheetSimpanan->setCellValue('BY'.$otherRow, '=Sheet1!C17');
				$sheetSimpanan->setCellValue('CA'.$otherRow, '=T'.$otherRow.'+U'.$otherRow);


				$sheetKGT->setCellValue('C'.$sheet4Row, $member->project->project_name);
				$sheetKGT->setCellValue('D'.$sheet4Row, $member->fullname);
				$sheetKGT->setCellValue('E'.$sheet4Row, $member->nik_koperasi);
				$sheetKGT->setCellValue('I'.$sheet4Row, $jBulan);
				$sheetKGT->setCellValue('L'.$sheet4Row, $pokok);

				$sheetKGT->setCellValue('F'.$otherRow, '=Sheet1!C9/Sheet3!H'.$otherRow);
				$sheetKGT->setCellValue('H'.$otherRow, '=Sheet1!C17');
				$sheetKGT->setCellValue('J'.$otherRow, '=IFERROR(I7'.$sheet4Row.'/$I$'.$sheet4Row.',0)*Sheet1!C26');
				$sheetKGT->setCellValue('L'.$otherRow, '=IFERROR(Sheet1!C20/Sheet1!D4,0)');
				$sheetKGT->setCellValue('M'.$otherRow, $status);

				$sheetWajib->setCellValue('A'.$sheet4Row, $no);
				$sheetWajib->setCellValue('B'.$sheet4Row, $member->nik_koperasi);
				$sheetWajib->setCellValue('C'.$sheet4Row, $member->fullname);
				$sheetWajib->setCellValue('D'.$sheet4Row, '=Sheet3!M'.$otherRow);
				$sheetWajib->setCellValue('E'.$sheet4Row, '=D'.$sheet4Row);
				$sheetWajib->setCellValue('F'.$sheet4Row, '=Sheet1!D3');
				$sheetWajib->setCellValue('G'.$sheet4Row, '=Sheet1!D21');
				$sheetWajib->setCellValue('H'.$sheet4Row, '=F'.$sheet4Row.'*G'.$sheet4Row);
				$sheetWajib->setCellValue('I'.$sheet4Row, '=IFERROR(D'.$sheet4Row.'/E'.$sheet4Row.',0)*H'.$sheet4Row);


				$sheetSukarela->setCellValue('B'.$sheet4Row, $no);
				$sheetSukarela->setCellValue('C'.$sheet4Row, $member->fullname);
				$sheetSukarela->setCellValue('D'.$sheet4Row, $member->nik_koperasi);

				$sheetLainnya->setCellValue('B'.$sheet4Row, $no);
				$sheetLainnya->setCellValue('C'.$sheet4Row, $member->fullname);

				$sheetJasa->setCellValue('B'.$sheet4Row, $no);
				$sheetJasa->setCellValue('C'.$sheet4Row, $member->fullname);
				$sheetJasa->setCellValue('D'.$sheet4Row, $member->nik_koperasi);
				$sheetJasa->setCellValue('K'.$sheet4Row, $status);

			}
			$endotherRow = $otherRow + 1;
			$sheetSimpanan->setCellValue('H'.$endotherRow, '=SUM(H5:H'.$otherRow.')');
			$sheetSimpanan->setCellValue('I'.$endotherRow, '=SUM(I5:I'.$otherRow.')');

			$sumRow4 = $sheet4Row + 1;
			$komposisi = $sheet4Row + 2;
			$balance = $sheet4Row + 3;

			$sheetKGT->setCellValue('F'.$sumRow4, '=SUM(F7:F'.$sheet4Row.')');
			$sheetKGT->setCellValue('G'.$sumRow4, '=SUM(G7:G'.$sheet4Row.')');
			$sheetKGT->setCellValue('H'.$sumRow4, '=SUM(H7:H'.$sheet4Row.')');
			$sheetKGT->setCellValue('J'.$sumRow4, '=SUM(J7:I'.$sheet4Row.')');
			$sheetKGT->setCellValue('K'.$sumRow4, '=SUM(K7:K'.$sheet4Row.')');
			$sheetKGT->setCellValue('L'.$sumRow4, '=SUM(L7:L'.$sheet4Row.')');

			$sheetKGT->setCellValue('D'.$komposisi, 'Komposisi');
			$sheetKGT->setCellValue('F'.$komposisi, '=Sheet1!C9');
			$sheetKGT->setCellValue('G'.$komposisi, '=Sheet1!C11');
			$sheetKGT->setCellValue('H'.$komposisi, '=Sheet1!C15');
			$sheetKGT->setCellValue('J'.$komposisi, '=Sheet1!C26');
			$sheetKGT->setCellValue('K'.$komposisi, '=Sheet1!D4');
			$sheetKGT->setCellValue('L'.$komposisi, '=Sheet1!C20');

			$sheetKGT->setCellValue('D'.$balance, 'Balance');
			$sheetKGT->setCellValue('F'.$balance, '=F'.$komposisi.'-F'.$sumRow4);
			$sheetKGT->setCellValue('G'.$balance, '=G'.$komposisi.'-G'.$sumRow4);
			$sheetKGT->setCellValue('H'.$balance, '=H'.$komposisi.'-H'.$sumRow4);
			$sheetKGT->setCellValue('J'.$balance, '=J'.$komposisi.'-J'.$sumRow4);
			$sheetKGT->setCellValue('K'.$balance, '=K'.$komposisi.'-K'.$sumRow4);
			$sheetKGT->setCellValue('L'.$balance, '=L'.$komposisi.'-L'.$sumRow4);

			$sheetWajib->setCellValue('D'.$sumRow4, '=SUM(D7:D'.$sheet4Row.')');



		})->export('xlsx');

	}


}
