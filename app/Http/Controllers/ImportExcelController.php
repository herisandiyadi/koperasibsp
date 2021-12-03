<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use App\Branch;
use App\ConfigDepositMembers;
use App\Helpers\CsvToArray;
use App\Helpers\cutOff;
use App\Helpers\ReverseData;
use App\Level;
use App\Loan;
use App\Member;
use App\MemberPlafon;
use App\Position;
use App\Project;
use App\Region;
use App\Resign;
use App\TotalDepositMember;
use App\TsDeposits;
use App\TsDepositsDetail;
use App\TsLoans;
use App\TsLoansDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImportExcelController extends Controller
{
    public function import_member()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/member.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		$header = array('status', 'project_code', 'project_name', 'no_koperasi_lama', 'nama', 'no_register', 'no_rekening', 'bank', 'ktp', 'alamat', 'no_hp', 'joint_date', 'end_date', 'special_member', 'plafon', 'jabatan', 'id_wilayah', 'code_area', 'pks_awal', 'pks_akhir', 'pks', 'email', 'pokok', 'wajib', 'sukarela');
		$data = $Csv->csv_combine_to_array($csv_data, $header);
		$collection = collect($data);
		foreach ($collection as $val) {
			if($val['email'] != ''){


			$validator = \Validator::make(['email' => $val['email']],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $val['email'];
			}else{
				$email = $val['email'] . '@gmail.com';

			}
			$val['status'] = str_replace('ï»¿', '', $val['status']);

			$explode = explode('-', $val['no_koperasi_lama']);
			$permanent = 0;
			$ket = '';
			$status = 0;
			if($val['status'] == 'AKTIF')
			{
				$status = 1;
				$ket = $val['status'];
			}
			if ($val['pks_awal'] == 'TETAP') {
				$permanent = 1;
				$start = null;
				$end = null;
			} elseif ($val['pks_awal'] == 'MENINGGAL') {
				$start = null;
				$end = null;
			} elseif ($val['pks_awal'] == 'RESIGN') {
				$start = null;
				$end = null;
			} elseif (($val['joint_date'] != 'TETAP' || $val['joint_date'] != 'MENINGGAL') && $val['status'] == 'AKTIF') {
				$start = Carbon::parse($val['joint_date']);
				$end = Carbon::parse($val['end_date']);
			} else {
				$start = Carbon::parse($val['joint_date']);
				$end = Carbon::parse($val['end_date']);
			}

			$user = new User();
			$user->name = $val['nama'];
			$user->email = $email;
			$user->username = $global->getBspNumber();
			$user->position_id = 14;
			$user->password = \Hash::make($explode[1]);
			$user->save();
			$user->assignRole('MEMBER');


			$member = new Member();
			$member->nik = $val['ktp'];
			$member->nik_koperasi_lama = $val['no_koperasi_lama'];
			$member->nik_bsp = $val['no_register'];
			$member->nik_koperasi = $global->getBspNumber();
			$member->user_id = $user->id;
			$member->project_id = null;
			$member->region_id = null;
			$member->branch_id = null;
			$member->position_id = 14;
			$member->first_name = $val['nama'];
			$member->address = $val['alamat'];
			$member->phone_number = $val['no_hp'];
			$member->join_date = $val['joint_date'] == 0 ? now() : Carbon::parse($val['joint_date']);
			$member->start_date = $start;
			$member->end_date = $end;
			$member->special = $val['special_member'] == 'YES' ? 'owner' : 'user';
			$member->is_active = $status;
			$member->is_permanent = $permanent;
			$member->keterangan = $ket;
			$member->email = $user->email;
			$member->verified_at = now();

			$region = Region::where('code', $val['code_area']);
			if ($region->count() > 0) {
				$region = $region->first();
				$member->region_id = $region->id;
				$branch = Branch::where('region_id', $region->id);
				if ($branch->count() > 0) {
					$member->branch_id = $branch->first()->id;
				}

				$project = Project::where('id', $val['project_code']);
				if ($project->count() > 0) {
					$member->project_id = $project->first()->id;
				}
			}

			$member->save();

			$plafond = new MemberPlafon();
			$plafond->member_id = $member->id;
			$plafond->nominal = $val['plafon'];
			$plafond->save();

			if ($val['wajib'] != '') {
				$simpanan_wajib = new ConfigDepositMembers();
				$simpanan_wajib->type = 'wajib';
				$simpanan_wajib->value = $val['wajib'];
				$simpanan_wajib->member_id = $member->id;
				$simpanan_wajib->save();
			}

			if ($val['pokok'] != '' && $val['pokok'] != 0) {
				$simpanan_pokok = new ConfigDepositMembers();
				$simpanan_pokok->type = 'pokok';
				$simpanan_pokok->value = $val['pokok'];
				$simpanan_pokok->member_id = $member->id;
				$simpanan_pokok->save();
			}

			if ($val['sukarela'] != '') {
				$simpanan_sukarela = new ConfigDepositMembers();
				$simpanan_sukarela->type = 'sukarela';
				$simpanan_sukarela->value = $val['sukarela'];
				$simpanan_sukarela->member_id = $member->id;
				$simpanan_sukarela->save();
			}

			if ($val['no_rekening'] != '') {
				$member_bank = new Bank();
				$member_bank->member_id = $member->id;
				$member_bank->bank_account_name = $val['nama'];
				$member_bank->bank_account_number = $val['no_rekening'];
				$member_bank->bank_name = $val['bank'];
				$member_bank->save();
			}
		}
	}
		return "saved";
	}

	public function import_member_pinjaman()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/pinjaman_2.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();
				$pinjaman = [
					[
						'id' => $val[1],
						'angsuran' => $val[2],
						'jasa' => $val[3],
						'tenor' => $val[4],
						'satuan' => $val[5],
						'tgl_cair' => $val[6],
						'start' => $val[7],
						'end' => $val[8],
						'member_id' => $member['id']
					],
					[
						'id' => $val[10],
						'angsuran' => $val[11],
						'jasa' => $val[12],
						'tenor' => $val[13],
						'satuan' => $val[14],
						'tgl_cair' => $val[15],
						'start' => $val[16],
						'end' => $val[17],
						'member_id' => $member['id']
					],
					[
						'id' => $val[19],
						'angsuran' => $val[20],
						'jasa' => $val[21],
						'tenor' => $val[22],
						'satuan' => $val[23],
						'tgl_cair' => $val[24],
						'start' => $val[25],
						'end' => $val[26],
						'member_id' => $member['id']
					],
					[
						'id' => $val[28],
						'angsuran' => $val[29],
						'jasa' => $val[30],
						'tenor' => $val[31],
						'satuan' => $val[32],
						'tgl_cair' => $val[33],
						'start' => $val[34],
						'end' => $val[35],
						'member_id' => $member['id']
					],
					[
						'id' => $val[37],
						'angsuran' => $val[38],
						'jasa' => $val[39],
						'tenor' => $val[40],
						'satuan' => $val[41],
						'tgl_cair' => $val[42],
						'start' => $val[43],
						'end' => $val[44],
						'member_id' => $member['id']
					],
					[
						'id' => $val[46],
						'angsuran' => $val[47],
						'jasa' => $val[48],
						'tenor' => $val[49],
						'satuan' => $val[50],
						'tgl_cair' => $val[51],
						'start' => $val[52],
						'end' => $val[53],
						'member_id' => $member['id']
					],
					[
						'id' => $val[55],
						'angsuran' => $val[56],
						'jasa' => $val[57],
						'tenor' => $val[58],
						'satuan' => $val[59],
						'tgl_cair' => $val[60],
						'start' => $val[61],
						'end' => $val[62],
						'member_id' => $member['id']
					],
					[
						'id' => $val[64],
						'angsuran' => $val[65],
						'jasa' => $val[66],
						'tenor' => $val[67],
						'satuan' => $val[68],
						'plafon' => $val[69],
						'tgl_cair' => $val[70],
						'start' => $val[71],
						'end' => $val[72],
						'member_id' => $member['id']
					],
					[
						'id' => $val[74],
						'angsuran' => $val[75],
						'jasa' => $val[76],
						'tenor' => $val[77],
						'satuan' => $val[78],
						'plafon' => $val[79],
						'tgl_cair' => $val[80],
						'start' => $val[81],
						'end' => $val[82],
						'member_id' => $member['id']
					],
					[
						'id' => $val[84],
						'angsuran' => $val[85],
						'jasa' => $val[86],
						'tenor' => $val[87],
						'satuan' => $val[88],
						'plafon' => $val[89],
						'tgl_cair' => $val[90],
						'start' => $val[91],
						'end' => $val[92],
						'member_id' => $member['id']
					],
					[
						'id' => $val[94],
						'angsuran' => $val[95],
						'jasa' => $val[96],
						'tenor' => $val[97],
						'satuan' => $val[98],
						'plafon' => $val[99],
						'tgl_cair' => $val[100],
						'start' => $val[101],
						'end' => $val[102],
						'member_id' => $member['id']
					],
					[
						'id' => $val[104],
						'angsuran' => $val[105],
						'jasa' => $val[106],
						'tenor' => $val[107],
						'satuan' => $val[108],
						'plafon' => $val[109],
						'tgl_cair' => $val[110],
						'start' => $val[111],
						'end' => $val[112],
						'member_id' => $member['id']
					],
					[
						'id' => $val[114],
						'angsuran' => $val[115],
						'jasa' => $val[116],
						'tenor' => $val[117],
						'satuan' => $val[118],
						'plafon' => $val[119],
						'tgl_cair' => $val[120],
						'start' => $val[121],
						'end' => $val[122],
						'member_id' => $member['id']
					],
					[
						'id' => $val[124],
						'angsuran' => $val[125],
						'jasa' => $val[126],
						'tenor' => $val[127],
						'satuan' => $val[128],
						'tgl_cair' => $val[129],
						'start' => $val[130],
						'end' => $val[131],
						'member_id' => $member['id']
					],
					[
						'id' => $val[133],
						'angsuran' => $val[134],
						'jasa' => $val[135],
						'tenor' => $val[136],
						'satuan' => $val[137],
						'tgl_cair' => $val[138],
						'start' => $val[139],
						'end' => $val[140],
						'member_id' => $member['id']
					]
				];

				foreach($pinjaman as $loan){
					if($loan['jasa'] != '')
					{
						if($loan['id'] == '15'){
							$angsuranCheck = false;
							if($loan['angsuran'] !== "")
							{
								$angsuranCheck = true;
							}
							$this->saveLoanImportBisnis($loan, $member['id'], $loan['id'], $loan['angsuran'], $loan['jasa'], $loan['tgl_cair'], $loan['start'], $loan['end'], $loan['plafon'], $angsuranCheck);
						}else{
							$this->saveLoanImport($loan, $member['id'], $loan['id'], $loan['angsuran'], $loan['jasa'], $loan['tgl_cair'], $loan['start'], $loan['end']);
						}
					}
				}
			}
		}
	}

	public function saveLoanImport($loan, $member_id, $loan_id, $angsuran, $jasa, $tgl_cair, $start, $end, $plafon = '0', $angsuranCheck = false){
		$from = Carbon::parse($start)->subMonth(1);
		$to = Carbon::parse($end);
		$diff_in_months = $to->diffInMonths($from);
		$cutoff = cutOff::getCutoff();
		$gte_loan1 = $to->gte($cutoff);
		$loan_value = (int) $angsuran * $diff_in_months;


		$loanNumber = new GlobalController();
		$loan = new TsLoans();
		$loan->loan_number = $loanNumber->getLoanNumber();
		$loan->member_id = $member_id;
		$loan->start_date = Carbon::parse($start);
		$loan->end_date = Carbon::parse($end);

		$loan->loan_id = $loan_id;
		$loan->value = $angsuran;
		if(!$gte_loan1){
			$loan->approval = 'lunas';
		}else{
			$loan->approval = 'belum lunas';
		}
		$loan->period = $diff_in_months;
		$loan->save();

		$b1 = 1;
		$in_period = 0;
		for ($a1 = 0; $a1 < $diff_in_months; $a1++) {

			$paydated = Carbon::parse($start)->addMonth($a1);
			$cutoff = cutOff::getCutoff();
			$gte_detail = $paydated->gte($cutoff);

			$loan_detail = new TsLoansDetail();
			$loan_detail->loan_id = $loan->id;
			$loan_detail->loan_number = $loan->loan_number;
			$loan_detail->value = $angsuran;
			$loan_detail->service = $jasa;
			$loan_detail->pay_date = $paydated;
			$loan_detail->in_period = $b1 + $a1;

			if(!$gte_detail){
				$loan_detail->approval = 'lunas';
				$in_period = $b1 + $a1;
			}else{
				$loan_detail->approval = 'belum lunas';
			}

			$loan_detail->save();

		}
		$loanValue = $angsuran * $diff_in_months;
		$loan->in_period = $in_period;
		$loan->value = $loanValue;
		$loan->save();
    }

	public function saveLoanImportBisnis($loan, $member_id, $loan_id, $angsuran, $jasa, $tgl_cair, $start, $end, $plafon = '0', $angsuranCheck = false){
		$from = Carbon::parse($start)->subMonth(1);
		$to = Carbon::parse($end);
		$diff_in_months = $to->diffInMonths($from);
		$cutoff = cutOff::getCutoff();
		$gte_loan1 = $to->gte($cutoff);
		$angsuranBisnis = $angsuran;
		if($angsuran == 0 || $angsuran == ""){
			$loan_value = 0;
			$angsuran = 0;
		}else{
			$loan_value = (int) $angsuran * $diff_in_months;
		}


		$loanNumber = new GlobalController();
		$loan = new TsLoans();
		$loan->loan_number = $loanNumber->getLoanNumber();
		$loan->member_id = $member_id;
		$loan->start_date = Carbon::parse($start);
		$loan->end_date = Carbon::parse($end);

		$loan->loan_id = $loan_id;
		$loan->value = $angsuran;
		if(!$gte_loan1){
			$loan->approval = 'lunas';
		}else{
			$loan->approval = 'belum lunas';
		}
		$loan->period = $diff_in_months;
		$loan->save();

		$b1 = 1;
		$in_period = 0;
		for ($a1 = 0; $a1 < $diff_in_months; $a1++) {

			$paydated = Carbon::parse($start)->addMonth($a1);
			$cutoff = cutOff::getCutoff();
			$gte_detail = $paydated->gte($cutoff);

			$loan_detail = new TsLoansDetail();
			$loan_detail->loan_id = $loan->id;
			$loan_detail->loan_number = $loan->loan_number;
			$loan_detail->value = $angsuran;
			$loan_detail->service = $jasa;
			$loan_detail->pay_date = $paydated;
			$loan_detail->in_period = $b1 + $a1;

			if(!$gte_detail){
				$loan_detail->approval = 'lunas';
				$in_period = $b1 + $a1;
			}else{
				$loan_detail->approval = 'belum lunas';
			}

			if(($b1 + $a1) === $diff_in_months && !$angsuranCheck){
				$loan_detail->value = $plafon;
			}

			$loan_detail->save();

		}
		$loanValue = $angsuran * $diff_in_months;
		$loan->in_period = $in_period;
		if($plafon !== '0')
		{
			$loan->value = $plafon;
		}else{
			$loan->value = $loanValue;

		}
		$loan->save();
    }

	public function import_member_pokok_kredit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_pokok_kredit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();
				$simpanans = [
					[
						'id' => 1,
						'nominal' => $val[4],
						'tanggal' => "2020-12-31",
					],
				];
				foreach($simpanans as $simpanan){
					$this->saveDepositImport($member->id, $simpanan['id'], $simpanan['nominal'], $simpanan['tanggal'], 'debit', 'pokok', '');

				}
			}
		}

	}

	public function saveDepositImport($member_id, $ms_deposit_id, $total_deposit, $post_date, $type, $deposit_type, $desc)
    {
        $payment_date = Carbon::parse($post_date);
        $cutoff = cutOff::getCutoff();
        $gte_deposit = $payment_date->gte($cutoff);
        $global = new GlobalController();

        $deposit = new TsDeposits();
        $deposit->member_id = $member_id;
        $deposit->deposit_number = $global->getDepositNumber();
        $deposit->ms_deposit_id = $ms_deposit_id;
        $deposit->type = $type;
        $deposit->deposits_type = $deposit_type;
        $deposit->total_deposit = abs($total_deposit);
        $deposit->post_date = $payment_date;

        if(!$gte_deposit){
            $deposit->status = 'paid';
        }else{
            $deposit->status = 'unpaid';
        }
        $deposit->desc = $desc;
        $deposit->save();
		dd($deposit);

        $deposit_detail = new TsDepositsDetail();
        $deposit_detail->transaction_id = $deposit->id;
        $deposit_detail->deposits_type = $deposit_type;
        if($type == 'credit'){
            $deposit_detail->debit = 0;
            $deposit_detail->credit = abs($total_deposit);
        }else{
            $deposit_detail->debit = abs($total_deposit);
            $deposit_detail->credit = 0;
        }

        $deposit_detail->total = abs($total_deposit);
        $deposit_detail->status = $deposit->status;
        $deposit_detail->payment_date = $payment_date;
        $deposit_detail->save();

		dd($deposit_detail);

    }

	public function import_member_pokok_debit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_pokok_debit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				// $simpanans2021 = [
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[5],
				// 		'tanggal' => $val[6],
				// 		'ket' => $val[7]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[8],
				// 		'tanggal' => $val[9],
				// 		'ket' => $val[10]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[11],
				// 		'tanggal' => $val[12],
				// 		'ket' => $val[13]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[14],
				// 		'tanggal' => $val[15],
				// 		'ket' => $val[16]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[17],
				// 		'tanggal' => $val[18],
				// 		'ket' => $val[19]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[20],
				// 		'tanggal' => $val[21],
				// 		'ket' => $val[22]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[23],
				// 		'tanggal' => $val[24],
				// 		'ket' => $val[25]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[26],
				// 		'tanggal' => $val[27],
				// 		'ket' => $val[28]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[29],
				// 		'tanggal' => $val[30],
				// 		'ket' => $val[31]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[32],
				// 		'tanggal' => $val[33],
				// 		'ket' => $val[34]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[35],
				// 		'tanggal' => $val[36],
				// 		'ket' => $val[37]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[38],
				// 		'tanggal' => $val[39],
				// 		'ket' => $val[40]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[41],
				// 		'tanggal' => $val[42],
				// 		'ket' => $val[43]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[44],
				// 		'tanggal' => $val[45],
				// 		'ket' => $val[46]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[47],
				// 		'tanggal' => $val[48],
				// 		'ket' => $val[49]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[50],
				// 		'tanggal' => $val[51],
				// 		'ket' => $val[52]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[53],
				// 		'tanggal' => $val[54],
				// 		'ket' => $val[55]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[56],
				// 		'tanggal' => $val[57],
				// 		'ket' => $val[58]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[59],
				// 		'tanggal' => $val[60],
				// 		'ket' => $val[61]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[62],
				// 		'tanggal' => $val[63],
				// 		'ket' => $val[64]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[65],
				// 		'tanggal' => $val[66],
				// 		'ket' => $val[67]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[68],
				// 		'tanggal' => $val[69],
				// 		'ket' => $val[70]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[71],
				// 		'tanggal' => $val[72],
				// 		'ket' => $val[73]
				// 	],
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[74],
				// 		'tanggal' => $val[75],
				// 		'ket' => $val[76]
				// 	]

				// ];
				$simpanans2020 = [
					[
						'id' => 1,
						'nominal' => $val[4],
						'tanggal' => "2021-01-01",
					],
				];


				foreach($simpanans2020 as $simpanan2020){
					if($simpanan2020['nominal'] != '' && $simpanan2020['nominal'] != '0' && $simpanan2020['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2020['id'], $simpanan2020['nominal'], $simpanan2020['tanggal'], 'debit', 'pokok', 'simpanan pokok 2020 + 2021');
					}
				}

				// foreach($simpanans2021 as $simpanan2021){
				// 	if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
				// 	{
				// 		$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'debit', 'pokok', $simpanan2021['ket']);
				// 	}
				// }
			}
		}

	}

	public function import_member_wajib_debit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_wajib_debit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				// $simpanans2021 = [
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[5],
				// 		'tanggal' => $val[6],
				// 		'ket' => $val[7]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[8],
				// 		'tanggal' => $val[9],
				// 		'ket' => $val[10]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[11],
				// 		'tanggal' => $val[12],
				// 		'ket' => $val[13]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[14],
				// 		'tanggal' => $val[15],
				// 		'ket' => $val[16]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[17],
				// 		'tanggal' => $val[18],
				// 		'ket' => $val[19]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[20],
				// 		'tanggal' => $val[21],
				// 		'ket' => $val[22]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[23],
				// 		'tanggal' => $val[24],
				// 		'ket' => $val[25]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[26],
				// 		'tanggal' => $val[27],
				// 		'ket' => $val[28]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[29],
				// 		'tanggal' => $val[30],
				// 		'ket' => $val[31]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[32],
				// 		'tanggal' => $val[33],
				// 		'ket' => $val[34]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[35],
				// 		'tanggal' => $val[36],
				// 		'ket' => $val[37]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[38],
				// 		'tanggal' => $val[39],
				// 		'ket' => $val[40]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[41],
				// 		'tanggal' => $val[42],
				// 		'ket' => $val[43]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[44],
				// 		'tanggal' => $val[45],
				// 		'ket' => $val[46]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[47],
				// 		'tanggal' => $val[48],
				// 		'ket' => $val[49]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[50],
				// 		'tanggal' => $val[51],
				// 		'ket' => $val[52]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[53],
				// 		'tanggal' => $val[54],
				// 		'ket' => $val[55]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[56],
				// 		'tanggal' => $val[57],
				// 		'ket' => $val[58]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[59],
				// 		'tanggal' => $val[60],
				// 		'ket' => $val[61]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[62],
				// 		'tanggal' => $val[63],
				// 		'ket' => $val[64]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[65],
				// 		'tanggal' => $val[66],
				// 		'ket' => $val[67]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[68],
				// 		'tanggal' => $val[69],
				// 		'ket' => $val[70]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[71],
				// 		'tanggal' => $val[72],
				// 		'ket' => $val[73]
				// 	],
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[74],
				// 		'tanggal' => $val[75],
				// 		'ket' => $val[76]
				// 	]

				// ];

				// $simpanans2020 = [
				// 	[
				// 		'id' => 2,
				// 		'nominal' => $val[3],
				// 		'tanggal' => "2020-12-31",
				// 	],
				// ];

				$simpanans2021 = [
					[
						'id' => 2,
						'nominal' => $val[4],
						'tanggal' => "2021-01-01",
					],
				];


				// foreach($simpanans2020 as $simpanan2020){
				// 	if($simpanan2020['nominal'] != '' && $simpanan2020['nominal'] != '0' && $simpanan2020['nominal'] != 0)
				// 	{
				// 		$this->saveDepositImport($member->id, $simpanan2020['id'], $simpanan2020['nominal'], $simpanan2020['tanggal'], 'debit', 'wajib', 'simpanan wajib 2020');
				// 	}
				// }

				foreach($simpanans2021 as $simpanan2021){
					if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'debit', 'wajib', 'simpanan wajib 2020 + 2021');
					}
				}
			}
		}

	}

	public function import_member_wajib_kredit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_wajib_kredit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				$simpanans2021 = [
					[
						'id' => 2,
						'nominal' => $val[5],
						'tanggal' => $val[6],
						'ket' => $val[7]
					],
					[
						'id' => 2,
						'nominal' => $val[8],
						'tanggal' => $val[9],
						'ket' => $val[10]
					],
					[
						'id' => 2,
						'nominal' => $val[11],
						'tanggal' => $val[12],
						'ket' => $val[13]
					],
					[
						'id' => 2,
						'nominal' => $val[14],
						'tanggal' => $val[15],
						'ket' => $val[16]
					],
					[
						'id' => 2,
						'nominal' => $val[17],
						'tanggal' => $val[18],
						'ket' => $val[19]
					],
					[
						'id' => 2,
						'nominal' => $val[20],
						'tanggal' => $val[21],
						'ket' => $val[22]
					],
					[
						'id' => 2,
						'nominal' => $val[23],
						'tanggal' => $val[24],
						'ket' => $val[25]
					],
					[
						'id' => 2,
						'nominal' => $val[26],
						'tanggal' => $val[27],
						'ket' => $val[28]
					],
					[
						'id' => 2,
						'nominal' => $val[29],
						'tanggal' => $val[30],
						'ket' => $val[31]
					],
					[
						'id' => 2,
						'nominal' => $val[32],
						'tanggal' => $val[33],
						'ket' => $val[34]
					],
					[
						'id' => 2,
						'nominal' => $val[35],
						'tanggal' => $val[36],
						'ket' => $val[37]
					],
					[
						'id' => 2,
						'nominal' => $val[38],
						'tanggal' => $val[39],
						'ket' => $val[40]
					]

				];

				foreach($simpanans2021 as $simpanan2021){
					if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'credit', 'wajib', $simpanan2021['ket']);
					}
				}
			}
		}

	}

	public function import_member_sukarela_debit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_sukarela_debit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				$simpanans2021 = [
					[
						'id' => 3,
						'nominal' => $val[5],
						'tanggal' => $val[6],
						'ket' => $val[7]
					],
					[
						'id' => 3,
						'nominal' => $val[8],
						'tanggal' => $val[9],
						'ket' => $val[10]
					],
					[
						'id' => 3,
						'nominal' => $val[11],
						'tanggal' => $val[12],
						'ket' => $val[13]
					],
					[
						'id' => 3,
						'nominal' => $val[14],
						'tanggal' => $val[15],
						'ket' => $val[16]
					],
					[
						'id' => 3,
						'nominal' => $val[17],
						'tanggal' => $val[18],
						'ket' => $val[19]
					],
					[
						'id' => 3,
						'nominal' => $val[20],
						'tanggal' => $val[21],
						'ket' => $val[22]
					],
					[
						'id' => 3,
						'nominal' => $val[23],
						'tanggal' => $val[24],
						'ket' => $val[25]
					],
					[
						'id' => 3,
						'nominal' => $val[26],
						'tanggal' => $val[27],
						'ket' => $val[28]
					],
					[
						'id' => 3,
						'nominal' => $val[29],
						'tanggal' => $val[30],
						'ket' => $val[31]
					],
					[
						'id' => 3,
						'nominal' => $val[32],
						'tanggal' => $val[33],
						'ket' => $val[34]
					],
					[
						'id' => 3,
						'nominal' => $val[35],
						'tanggal' => $val[36],
						'ket' => $val[37]
					],
					[
						'id' => 3,
						'nominal' => $val[38],
						'tanggal' => $val[39],
						'ket' => $val[40]
					],
					[
						'id' => 3,
						'nominal' => $val[41],
						'tanggal' => $val[42],
						'ket' => $val[43]
					],
					[
						'id' => 3,
						'nominal' => $val[44],
						'tanggal' => $val[45],
						'ket' => $val[46]
					],
					[
						'id' => 3,
						'nominal' => $val[47],
						'tanggal' => $val[48],
						'ket' => $val[49]
					],
					[
						'id' => 3,
						'nominal' => $val[50],
						'tanggal' => $val[51],
						'ket' => $val[52]
					],
					[
						'id' => 3,
						'nominal' => $val[53],
						'tanggal' => $val[54],
						'ket' => $val[55]
					],
					[
						'id' => 3,
						'nominal' => $val[56],
						'tanggal' => $val[57],
						'ket' => $val[58]
					],
					[
						'id' => 3,
						'nominal' => $val[59],
						'tanggal' => $val[60],
						'ket' => $val[61]
					],
					[
						'id' => 3,
						'nominal' => $val[62],
						'tanggal' => $val[63],
						'ket' => $val[64]
					],
					[
						'id' => 3,
						'nominal' => $val[65],
						'tanggal' => $val[66],
						'ket' => $val[67]
					],
					[
						'id' => 3,
						'nominal' => $val[68],
						'tanggal' => $val[69],
						'ket' => $val[70]
					],
					[
						'id' => 3,
						'nominal' => $val[71],
						'tanggal' => $val[72],
						'ket' => $val[73]
					],
					[
						'id' => 3,
						'nominal' => $val[74],
						'tanggal' => $val[75],
						'ket' => $val[76]
					],
					[
						'id' => 3,
						'nominal' => $val[74],
						'tanggal' => $val[75],
						'ket' => $val[79]
					]

				];
				$simpanans2020 = [
					[
						'id' => 3,
						'nominal' => $val[3],
						'tanggal' => "2020-12-31",
					],
				];
				// $simpanans2021 = [
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[4],
				// 		'tanggal' => "2021-05-31",
				// 	],
				// ];
				foreach($simpanans2020 as $simpanan2020){
					if($simpanan2020['nominal'] != '' && $simpanan2020['nominal'] != '0' && $simpanan2020['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2020['id'], $simpanan2020['nominal'], $simpanan2020['tanggal'], 'debit', 'sukarela', 'simpanan 2020');
					}
				}

				foreach($simpanans2021 as $simpanan2021){
					if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'debit', 'sukarela', $simpanan2021['ket']);
					}
				}
			}
		}

	}

	public function import_member_sukarela_kredit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_sukarela_kredit_2.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			// dd($val);
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				$simpanans2021 = [
					[
						'id' => 3,
						'nominal' => $val[5],
						'tanggal' => $val[6],
						'ket' => $val[7]
					],
					[
						'id' => 3,
						'nominal' => $val[8],
						'tanggal' => $val[9],
						'ket' => $val[10]
					],
					[
						'id' => 3,
						'nominal' => $val[11],
						'tanggal' => $val[12],
						'ket' => $val[13]
					],
					[
						'id' => 3,
						'nominal' => $val[14],
						'tanggal' => $val[15],
						'ket' => $val[16]
					],
					[
						'id' => 3,
						'nominal' => $val[17],
						'tanggal' => $val[18],
						'ket' => $val[19]
					],
					[
						'id' => 3,
						'nominal' => $val[20],
						'tanggal' => $val[21],
						'ket' => $val[22]
					],
					[
						'id' => 3,
						'nominal' => $val[23],
						'tanggal' => $val[24],
						'ket' => $val[25]
					],
					[
						'id' => 3,
						'nominal' => $val[26],
						'tanggal' => $val[27],
						'ket' => $val[28]
					],
					[
						'id' => 3,
						'nominal' => $val[29],
						'tanggal' => $val[30],
						'ket' => $val[31]
					],
					[
						'id' => 3,
						'nominal' => $val[32],
						'tanggal' => $val[33],
						'ket' => $val[34]
					],
					[
						'id' => 3,
						'nominal' => $val[35],
						'tanggal' => $val[36],
						'ket' => $val[37]
					],
					[
						'id' => 3,
						'nominal' => $val[38],
						'tanggal' => $val[39],
						'ket' => $val[40]
					],
					[
						'id' => 3,
						'nominal' => $val[41],
						'tanggal' => $val[42],
						'ket' => $val[43]
					],
					[
						'id' => 3,
						'nominal' => $val[44],
						'tanggal' => $val[45],
						'ket' => $val[46]
					],
					[
						'id' => 3,
						'nominal' => $val[47],
						'tanggal' => $val[48],
						'ket' => $val[49]
					],
					[
						'id' => 3,
						'nominal' => $val[50],
						'tanggal' => $val[51],
						'ket' => $val[52]
					],
					[
						'id' => 3,
						'nominal' => $val[53],
						'tanggal' => $val[54],
						'ket' => $val[55]
					],
					[
						'id' => 3,
						'nominal' => $val[56],
						'tanggal' => $val[57],
						'ket' => $val[58]
					],
					[
						'id' => 3,
						'nominal' => $val[59],
						'tanggal' => $val[60],
						'ket' => $val[61]
					],
					[
						'id' => 3,
						'nominal' => $val[62],
						'tanggal' => $val[63],
						'ket' => $val[64]
					],
					[
						'id' => 3,
						'nominal' => $val[65],
						'tanggal' => $val[66],
						'ket' => $val[67]
					],
					[
						'id' => 3,
						'nominal' => $val[68],
						'tanggal' => $val[69],
						'ket' => $val[70]
					],
					[
						'id' => 3,
						'nominal' => $val[71],
						'tanggal' => $val[72],
						'ket' => $val[73]
					],
					[
						'id' => 3,
						'nominal' => $val[74],
						'tanggal' => $val[75],
						'ket' => $val[76]
					],
					[
						'id' => 3,
						'nominal' => $val[74],
						'tanggal' => $val[75],
						'ket' => $val[79]
					],
					[
						'id' => 3,
						'nominal' => $val[80],
						'tanggal' => $val[81],
						'ket' => $val[82]
					],
					[
						'id' => 3,
						'nominal' => $val[83],
						'tanggal' => $val[84],
						'ket' => $val[85]
					],
					[
						'id' => 3,
						'nominal' => $val[86],
						'tanggal' => $val[87],
						'ket' => $val[88]
					],
					[
						'id' => 3,
						'nominal' => $val[89],
						'tanggal' => $val[90],
						'ket' => $val[91]
					],
					[
						'id' => 3,
						'nominal' => $val[92],
						'tanggal' => $val[93],
						'ket' => $val[94]
					],
					[
						'id' => 3,
						'nominal' => $val[95],
						'tanggal' => $val[96],
						'ket' => $val[97]
					],
					[
						'id' => 3,
						'nominal' => $val[98],
						'tanggal' => $val[99],
						'ket' => $val[100]
					],
					[
						'id' => 3,
						'nominal' => $val[101],
						'tanggal' => $val[102],
						'ket' => $val[103]
					],
					[
						'id' => 3,
						'nominal' => $val[104],
						'tanggal' => $val[105],
						'ket' => $val[106]
					],
					[
						'id' => 3,
						'nominal' => $val[107],
						'tanggal' => $val[108],
						'ket' => $val[109]
					],
					[
						'id' => 3,
						'nominal' => $val[110],
						'tanggal' => $val[111],
						'ket' => $val[112]
					]


				];
				// $simpanans2020 = [
				// 	[
				// 		'id' => 3,
				// 		'nominal' => $val[3],
				// 		'tanggal' => "2020-12-31",
				// 	],
				// ];
				// $simpanans2021 = [
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[4],
				// 		'tanggal' => "2021-05-31",
				// 	],
				// ];
				// foreach($simpanans2020 as $simpanan2020){
				// 	if($simpanan2020['nominal'] != '' && $simpanan2020['nominal'] != '0' && $simpanan2020['nominal'] != 0)
				// 	{
				// 		$this->saveDepositImport($member->id, $simpanan2020['id'], $simpanan2020['nominal'], $simpanan2020['tanggal'], 'debit', 'sukarela', 'simpanan 2020');
				// 	}
				// }

				foreach($simpanans2021 as $simpanan2021){
					if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'credit', 'sukarela', $simpanan2021['ket']);
					}
				}
			}
		}

	}

	public function import_member_shu_debit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_shu_debit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				// $simpanans2021 = [
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[5],
				// 		'tanggal' => $val[6],
				// 		'ket' => $val[7]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[8],
				// 		'tanggal' => $val[9],
				// 		'ket' => $val[10]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[11],
				// 		'tanggal' => $val[12],
				// 		'ket' => $val[13]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[14],
				// 		'tanggal' => $val[15],
				// 		'ket' => $val[16]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[17],
				// 		'tanggal' => $val[18],
				// 		'ket' => $val[19]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[20],
				// 		'tanggal' => $val[21],
				// 		'ket' => $val[22]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[23],
				// 		'tanggal' => $val[24],
				// 		'ket' => $val[25]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[26],
				// 		'tanggal' => $val[27],
				// 		'ket' => $val[28]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[29],
				// 		'tanggal' => $val[30],
				// 		'ket' => $val[31]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[32],
				// 		'tanggal' => $val[33],
				// 		'ket' => $val[34]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[35],
				// 		'tanggal' => $val[36],
				// 		'ket' => $val[37]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[38],
				// 		'tanggal' => $val[39],
				// 		'ket' => $val[40]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[41],
				// 		'tanggal' => $val[42],
				// 		'ket' => $val[43]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[44],
				// 		'tanggal' => $val[45],
				// 		'ket' => $val[46]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[47],
				// 		'tanggal' => $val[48],
				// 		'ket' => $val[49]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[50],
				// 		'tanggal' => $val[51],
				// 		'ket' => $val[52]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[53],
				// 		'tanggal' => $val[54],
				// 		'ket' => $val[55]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[56],
				// 		'tanggal' => $val[57],
				// 		'ket' => $val[58]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[59],
				// 		'tanggal' => $val[60],
				// 		'ket' => $val[61]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[62],
				// 		'tanggal' => $val[63],
				// 		'ket' => $val[64]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[65],
				// 		'tanggal' => $val[66],
				// 		'ket' => $val[67]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[68],
				// 		'tanggal' => $val[69],
				// 		'ket' => $val[70]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[71],
				// 		'tanggal' => $val[72],
				// 		'ket' => $val[73]
				// 	],
				// 	[
				// 		'id' => 5,
				// 		'nominal' => $val[74],
				// 		'tanggal' => $val[75],
				// 		'ket' => $val[76]
				// 	]

				// ];
				$simpanans2020 = [
					[
						'id' => 5,
						'nominal' => $val[4],
						'tanggal' => "2020-12-31",
					],
				];
				// $simpanans2021 = [
				// 	[
				// 		'id' => 1,
				// 		'nominal' => $val[4],
				// 		'tanggal' => "2021-05-31",
				// 	],
				// ];
				foreach($simpanans2020 as $simpanan2020){
					if($simpanan2020['nominal'] != '' && $simpanan2020['nominal'] != '0' && $simpanan2020['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2020['id'], $simpanan2020['nominal'], $simpanan2020['tanggal'], 'debit', 'shu', 'SHU DITAHAN');
					}
				}

				// foreach($simpanans2021 as $simpanan2021){
				// 	if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
				// 	{
				// 		$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'debit', 'shu', $simpanan2021['ket']);
				// 	}
				// }
			}
		}

	}

	public function import_member_lainnya_debit()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/simpanan_lainnya_debit.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();

				$simpanans2021 = [
					[
						'id' => 6,
						'nominal' => $val[2],
						'tanggal' => "2020-12-31"
					]

				];

				foreach($simpanans2021 as $simpanan2021){
					if($simpanan2021['nominal'] != '' && $simpanan2021['nominal'] != '0' && $simpanan2021['nominal'] != 0)
					{
						$this->saveDepositImport($member->id, $simpanan2021['id'], $simpanan2021['nominal'], $simpanan2021['tanggal'], 'debit', 'lainnya', 'simpanan lainnya');
					}
				}
			}
		}

	}

	public function update_project()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/import/project_new.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		$header = array('id', 'project_code', 'project_area', 'project_name', 'address', 'start_date', 'end_date', 'status', 'date_salary');
		$data = $Csv->csv_combine_to_array($csv_data, $header);
		$collection = collect($data);
		foreach($collection as $val)
		{
			$val['id'] = str_replace('ï»¿', '', $val['id']);
			$ms_project = Project::where('id', $val['id']);
			if($val['status'] == 'PERMANENT'){
				$val['start_date'] = null;
				$val['end_date'] = null;
			}else{
				$val['start_date'] = Carbon::parse($val['start_date']);
				$val['end_date'] = Carbon::parse($val['end_date']);

			}
			$val['status'] = ucwords(strtolower($val['status']));
			if($ms_project->count() > 0)
			{
				$ms_project = $ms_project->first();
				$ms_project->update($val);
			}else{
				$region = Region::where('name_area', $val['project_area']);
				if($region->count() > 0)
				{
					$val['region_id'] = $region->first()->id;
					$ms_project = new Project();
					$ms_project->create($val);
				}

			}
		}
	}

	public function update_simpanan_config()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/csv/update_config_simpanan.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}
			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();
				if($val[1] != ''){
					$configDeposit = new ConfigDepositMembers();
					$configDeposit->member_id = $member->id;
					$configDeposit->value = $val[1];
					$configDeposit->type = 'pokok';
					$configDeposit->save();
				}

				if($val[2] != ''){
					$configDeposit = new ConfigDepositMembers();
					$configDeposit->member_id = $member->id;
					$configDeposit->value = $val[2];
					$configDeposit->type = 'wajib';
					$configDeposit->save();
				}

				if($val[3] != ''){
					$configDeposit = new ConfigDepositMembers();
					$configDeposit->member_id = $member->id;
					$configDeposit->value = $val[3];
					$configDeposit->type = 'sukarela';
					$configDeposit->save();
				}

			}
		}

	}

	public function update_member_project()
	{
		\DB::disableQueryLog();
		$global = new GlobalController();
		$Csv = new \App\Helpers\CsvCombineArray();
		$file = base_path() . '/database/seeds/csv/update_member_project.csv';
		ini_set("memory_limit", "10056M");
		$csv = utf8_encode(file_get_contents($file));
		$array = explode("\r", $csv);
		$csv_data = array_map('str_getcsv', $array);
		foreach($csv_data as $val)
		{
			$repEmail = str_replace('ï»¿', '', $val[0]);
			$validator = \Validator::make(['email' => $repEmail],[
				'email' => 'required|email'
			]);

			if($validator->passes()){
				$email = $repEmail;
			}else{
				$email = $repEmail . '@gmail.com';
			}

			$member = Member::where('email', $email);
			if($member->count() > 0)
			{
				$member = $member->first();
				$member->project_id = $val[1];
				$member->save();
			}
		}

	}

	public function updatingDateWajib()
	{
		$depositWajib = TsDeposits::where('ms_deposit_id', 2)->get();
		foreach($depositWajib as $deposit)
		{
			$deposit->post_date = '2021-05-25';
			$deposit->update();
			foreach($deposit->detail as $detail)
			{
				$detail->payment_date = '2021-05-25';
				$detail->update();
			}
		}

		return 'completed';
	}


}
