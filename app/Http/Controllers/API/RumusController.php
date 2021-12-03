<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Input;

class RumusController extends Controller
{
	//
	public function plafon(Request $request)
	{
		$plafon = $request['plafon'];
		$tenor = $request['tenor'];
		$bunga = $request['bunga'] / 100;

		$cicilan = ($plafon / $tenor) + ($plafon * $bunga);
		$totalcicilan = $cicilan / (35 / 100);
		$nominalbunga = round($totalcicilan) * $tenor;
		$nominalbunga = $nominalbunga - $plafon;



		try {
			$user = auth()->userOrFail();
			$dataplafon = array(
			    'error' => false,
				'status' => 'success',
				'messages' => 'berhasil',
				'data' => array(
					'plafon' => round($totalcicilan),
					'pengajuan' => $plafon,
					'nominalbunga' => $nominalbunga
				)
			);
		} catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
			// do something
			$dataplafon = array(
			    'error' => true,
				'status' => 'failed',
				'messages' => 'not authorize',
				'data' => array(
					'plafon' => 0,
					'pengajuan' => 0,
					'nominalbunga' => 0
				)
			);
		}
		return $dataplafon;

		// return $dataplafon;
	}

	public function testauth(){
		try {
			$user = auth()->userOrFail();
		} catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
			// do something

			$user = 'gagal';
		}
		return $user;
	}

	public function shu(Request $request){

		$shu = $request['shu'];
		$cadangan_yhd = $request['cadangan_yhd'] / 100;
		$pendidikan = $request['pendidikan'] / 100;
		$sosial = $request['sosial'] / 100;
		$pendiri = $request['pendiri'] / 100;
		$pengurus_pengawas = $request['pengurus_pengawas'] / 100;
		$pengurus = $request['pengurus'] / 100;
		$ketua1 = $request['ketua1'] / 100;
		$sekretaris = $request['sekretaris'] / 100;
		$bendahara = $request['bendahara'] / 100;
		$pengawas = $request['pengawas'] / 100;
		$ketua2 = $request['ketua2'] / 100;
		$pengawas1 = $request['pengawas1'] / 100;
		$pengawas2 = $request['pengawas2'] / 100;
		$anggota = $request['anggota'] / 100;
		$keanggotaan = $request['keanggotaan'] / 100;
		$s_wajib = $request['s_wajib'] / 100;
		$s_lainnya = $request['s_lainnya'] / 100;
		$s_sukarela = $request['s_sukarela'] / 100;
		$jasa_pinjaman = $request['jasa_pinjaman'] / 100;
		$retail = $request['retail'] / 100;
		$senioritas = $request['senioritas'] / 100;
		$total_anggota = $request['total_anggota'] / 100;

		$pengurus_R = ($shu * $pengurus_pengawas) * $pengurus;

		$cadangan_yhd = $shu * $cadangan_yhd;
		$pendidikan = $shu * $pendidikan;
		$sosial = $shu * $sosial;
		$pendiri = $shu * $pendiri;
		$pengurus_pengawas = $shu * $pengurus_pengawas;
		$pengurus = $pengurus_R;
		$ketua1 = $shu * $ketua2;
		$sekretaris = $shu * $sekretaris;
		$bendahara = $shu * $bendahara;
		$pengawas = $pengurus_pengawas * $pengawas;
		$ketua2 = $shu * $ketua2;
		$pengawas1 = $shu * $pengawas1;
		$pengawas2 = $shu * $pengawas2;
		$anggota = $shu * $anggota;
		$keanggotaan = $shu * $keanggotaan;
		$s_wajib = $shu * $s_wajib;
		$s_lainnya = $shu * $s_lainnya;
		$s_sukarela = $shu * $s_sukarela;
		$jasa_pinjaman = $shu * $jasa_pinjaman;
		$retail = $shu * $retail;
		$senioritas = $shu * $senioritas;
		$total_anggota = $request['total_anggota'] / 100;

		$ttl = $keanggotaan + $s_wajib + $s_sukarela + $s_lainnya + $jasa_pinjaman + $retail + $senioritas;
		$total = $cadangan_yhd + $pendidikan + $sosial + $pendiri + $pengurus_pengawas + $anggota;


		$return = array(
				"shu" => $shu,
				"cadangan_yhd" => $cadangan_yhd,
				"pendidikan" => $pendidikan,
				"sosial" => $sosial,
				"pendiri" => $pendiri,
				"pengurus_pengawas" => $pengurus_pengawas,
				"pengurus" => $pengurus,
				"ketua1" => $ketua1,
				"sekretaris" => $sekretaris,
				"bendahara" => $bendahara,
				"pengawas" =>$pengawas,
				"ketua2" => $ketua2,
				"pengawas1" => $pengawas1,
				"pengawas2" => $pengawas2,
				"anggota" => $anggota,
				"keanggotaan" => $keanggotaan,
				"s_wajib" => $s_wajib,
				"s_lainnya" => $s_lainnya,
				"s_sukarela" => $s_sukarela,
				"jasa_pinjaman" => $jasa_pinjaman,
				"retail" => $retail,
				"senioritas" =>$senioritas,
				"ttl" => $ttl,
				"total" => $total,
				"total_anggota" => "2129"
		);

		return $return;
	}
}
