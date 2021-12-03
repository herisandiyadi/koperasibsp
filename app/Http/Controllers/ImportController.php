<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImportController extends Controller
{

    public function __construct()
    {
        set_time_limit(8000000);
    }

	public function getImport()
	{
		return view('import');
	}

	public function parseImport(Request $request)
	{

	    try {
            \DB::disableQueryLog();
            ini_set("memory_limit", "10056M");
            $path = $request->file('csv_file')->getRealPath();
            $csv = utf8_encode(file_get_contents($path));
            $array = explode("\n", $csv);
            $csv_data = array_map('str_getcsv', $array);

//            dd($csv_data);
//            $csv_data = array_slice($data, 0, 150);
            $global = new GlobalController();
//        return $global->getDepositNumber();

            foreach ($csv_data as $key => $val) {
//		    return $val;
//		    return (int)$val[18]);
//		    if($val[188] != '' && $val[188] != 0){
//                return $val[188]);
//            }
//		    return 123;
//			$from = Carbon::parse($val[15]);
//			$to = Carbon::parse($val[16]);
//			$diff_in_months = $to->diffInMonths($from) + 1;
                if (isset($val[3])) {

                    $validator = \Validator::make(['email' => $val[125]],[
                        'email' => 'required|email'
                    ]);

                    if($validator->passes()){
                        $email = $val[125];
                    }else{
                        $email = $val[125] . '@gmail.com';

                    }
                    $explode = explode('-', $val[2]);
//                    \Log::info($val[3]);
                    $permanent = 0;
                    $ket = '';
                    if ($val[122] == 'TETAP') {
                        $ket = $val[122];
                        $status = 1;
                        $permanent = 1;
                        $start = null;
                        $end = null;
                    } elseif ($val[122] == 'MENINGGAL') {
                        $ket = $val[122];
                        $status = 0;
                        $start = null;
                        $end = null;
                    } elseif ($val[122] == 'RESIGN') {
                        $ket = $val[122];
                        $status = 0;
                        $start = null;
                        $end = null;
                    } else {
                        $status = 1;
                        $permanent = 1;
                        $start = Carbon::parse($val[122]);
                        $end = Carbon::parse($val[123]);
                    }

                    $user = new User();
                    $user->name = $val[3];
                    $user->email = $email;
                    $user->username = $global->getBspNumber();
                    $user->position_id = 14;
                    $user->password = \Hash::make($explode[1]);
                    $user->save();
                    $user->assignRole('MEMBER');

                    $member = new Member();
                    $member->nik = $val[112];
                    $member->nik_koperasi_lama = $val[2];
                    $member->nik_bsp = $val[4];
                    $member->nik_koperasi = $global->getBspNumber();
                    $member->user_id = $user->id;
                    $member->project_id = null;
                    $member->region_id = null;
                    $member->branch_id = null;
                    $member->position_id = 14;
                    $member->first_name = $val[3];
                    $member->address = $val[113];
                    $member->phone_number = $val[114];
                    $member->join_date = $val[115] == 0 ? now() : Carbon::parse($val[115]);
                    $member->start_date = $start;
                    $member->end_date = $end;
                    $member->special = $val[117] == 'YES' ? 'owner' : 'user';
                    $member->is_active = ($val[0] == 'AKTIF' && $permanent) || $val[0] == 'MUTASI' || $val[0] == 'FLOATINGAN' || $permanent == 1 ? 1 : 0;
                    $member->is_permanent = $permanent;
                    $member->keterangan = $ket;
                    $member->email = $val[125] . '@gmail.com';
                    $member->verified_at = now();

                    $region = Region::where('code', $val[121]);
                    if ($region->count() > 0) {
                        $region = $region->first();
                        $member->region_id = $region->id;
                        $branch = Branch::where('region_id', $region->id);
                        if ($branch->count() > 0) {
                            $member->branch_id = $branch->first()->id;
                        }

                        $project = Project::where('id', $val[1]);
                        if ($project->count() > 0) {
                            $member->project_id = $project->first()->id;
                        }
                    }

//                $region = Project::where('code', $val[121]);

                    $member->save();

                    if ($val[122] == 'MENINGGAL') {
                        $resign = new Resign();
                        $resign->member_id = $member->id;
                        $resign->date = now()->format('Y-m-d');
                        $resign->note = 'ANGGOTA ' . $val[122];
                        $resign->reason = $val[122];
                        $resign->approval = 'approve';
                        $resign->is_resign = true;
                        $resign->save();

                    }

                    if ($val[122] == 'RESIGN') {
                        $resign = new Resign();
                        $resign->member_id = $member->id;
                        $resign->date = now()->format('Y-m-d');
                        $resign->note = 'ANGGOTA ' . $val[122];
                        $resign->reason = $val[122];
                        $resign->approval = 'approve';
                        $resign->is_resign = true;
                        $resign->save();

                    }

                    $plafond = new MemberPlafon();
                    $plafond->member_id = $member->id;
                    $plafond->nominal = $val[118];
                    $plafond->save();

                    if ($val[8] != '') {
                        $simpanan_wajib = new ConfigDepositMembers();
                        $simpanan_wajib->type = 'wajib';
                        $simpanan_wajib->value = $val[8];
                        $simpanan_wajib->member_id = $member->id;
                        $simpanan_wajib->save();
                    }

                    if ($val[298] != '' && $val[298] != 0) {
                        $simpanan_pokok = new ConfigDepositMembers();
                        $simpanan_pokok->type = 'pokok';
                        $simpanan_pokok->value = $val[298];
                        $simpanan_pokok->member_id = $member->id;
                        $simpanan_pokok->save();
                    }

                    if ($val[9] != '') {
                        $simpanan_sukarela = new ConfigDepositMembers();
                        $simpanan_sukarela->type = 'sukarela';
                        $simpanan_sukarela->value = $val[9];
                        $simpanan_sukarela->member_id = $member->id;
                        $simpanan_sukarela->save();
                    }

                    // pinjaman tunai lain
                    if ($val[10] != '' || $val[15] != 0 || $val[16] != 0) {
                        $this->saveLoanImport($member->id, $val[15], $val[16], $val[10], $val[11], $val[15], 1);
                    }

                    // pinjaman tunai lain 1
                    if ($val[17] != '' || $val[22] != 0 || $val[23] != 0) {
                        $this->saveLoanImport($member->id, $val[22], $val[23], $val[17], $val[18], $val[22], 11);
                    }

                    // pinjaman tunai lain 2
                    if ($val[24] != '' || $val[29] != 0 || $val[30] != 0) {
                        $this->saveLoanImport($member->id, $val[29], $val[30], $val[24], $val[25], $val[29], 11);
                    }

                    // pinjaman tunai lain 3
                    if ($val[31] != '' || $val[36] != 0 || $val[37] != 0) {
                        $this->saveLoanImport($member->id, $val[36], $val[37], $val[31], $val[32], $val[36], 11);
                    }

                    // pinjaman tunai lain 4
                    if ($val[38] != '' || $val[43] != 0 || $val[44] != 0) {
                        $this->saveLoanImport($member->id, $val[43], $val[44], $val[38], $val[39], $val[43], 11);
                    }

                    // pinjaman tunai lain 5
                    if ($val[45] != '' || $val[50] != 0 || $val[51] != 0) {
                        $this->saveLoanImport($member->id, $val[50], $val[51], $val[45], $val[46], $val[50], 11);
                    }

                    // pinjaman tunai lain 6
                    if ($val[52] != '' || $val[57] != 0 || $val[58] != 0) {
                        $this->saveLoanImport($member->id, $val[57], $val[58], $val[52], $val[53], $val[57], 11);
                    }

                    // pinjaman barang 1
                    if ($val[59] != '' || $val[64] != 0 || $val[65] != 0) {
                        $this->saveLoanImport($member->id, $val[64], $val[65], $val[59], $val[60], $val[64], 3);
                    }

                    // pinjaman barang 2
                    if ($val[66] != '' || $val[71] != 0 || $val[72] != 0) {
                        $this->saveLoanImport($member->id, $val[71], $val[72], $val[66], $val[67], $val[71], 3);
                    }

                    // pinjaman pendidikan
                    if ($val[73] != '' || $val[78] != 0 || $val[79] != 0) {
                        $this->saveLoanImport($member->id, $val[78], $val[79], $val[73], $val[74], $val[78], 4);
                    }

                    // pinjaman darurat
                    if ($val[80] != '' || $val[85] != 0 || $val[86] != 0) {
                        $this->saveLoanImport($member->id, $val[85], $val[86], $val[80], $val[81], $val[85], 5);
                    }

                    // pinjaman softloan
                    if ($val[87] != '' || $val[92] != 0 || $val[93] != 0) {
                        $this->saveLoanImport($member->id, $val[92], $val[93], $val[87], $val[88], $val[92], 10);
                    }

                    // pinjaman motorloan 1
                    if ($val[94] != '' && $val[95] != '') {
                        $this->saveLoanImport($member->id, $val[99], $val[100], $val[94], $val[95], $val[99], 9);
                    }
                    // pinjaman motorloan 1a
                    if ($val[94] != '' && $val[95] == '') {
                        $this->saveLoanImport($member->id, $val[99], $val[100], $val[94], 0, $val[99], 9);
                    }


                    // pinjaman bisnis 1a
                    if ($val[126] != '' && $val[130] != '') {
                        $this->saveLoanImport($member->id, $val[132], $val[133], $val[126], $val[127], $val[132], 15);
                    }

                    // pinjaman bisnis 1b
                    if ($val[126] == '' && $val[130] != '') {
                        $this->saveLoanImportJasa($member->id, $val[132], $val[133], $val[130], $val[127], $val[132], 15);
                        //($member_id, $start, $end, $value, $service, $paydate, $loan_id)
                    }

                    // pinjaman bisnis 2a
                    if ($val[134] != '' && $val[135] != '') {
                        $this->saveLoanImport($member->id, $val[140], $val[141], $val[134], $val[135], $val[140], 15);
                    }

                    // pinjaman bisnis 2b
                    if ($val[134] == '' && $val[135] != '') {
                        $this->saveLoanImportJasa($member->id, $val[140], $val[141], $val[138], $val[135], $val[132], 15);
                    }

                    // pinjaman bisnis 3a
                    if ($val[142] != '' && $val[143] != '') {
                        $this->saveLoanImport($member->id, $val[148], $val[149], $val[142], $val[143], $val[148], 15);
                    }

                    // pinjaman bisnis 3b
                    if ($val[142] == '' && $val[143] != '') {
                        $this->saveLoanImportJasa($member->id, $val[148], $val[149], $val[146], $val[143], $val[148], 15);
                    }

                    // pinjaman bisnis 4a
                    if ($val[150] != '' && $val[151] != '') {
                        $this->saveLoanImport($member->id, $val[156], $val[157], $val[150], $val[151], $val[156], 15);
                    }

                    // pinjaman bisnis 4b
                    if ($val[150] == '' && $val[151] != '') {
                        $this->saveLoanImportJasa($member->id, $val[156], $val[157], $val[154], $val[151], $val[156], 15);
                    }

                    // pinjaman bisnis 5a
                    if ($val[158] != '' && $val[159] != '') {
                        $this->saveLoanImport($member->id, $val[164], $val[165], $val[158], $val[159], $val[164], 15);
                    }

                    // pinjaman bisnis 5b
                    if ($val[158] == '' && $val[159] != '') {
                        $this->saveLoanImportJasa($member->id, $val[164], $val[165], $val[162], $val[159], $val[164], 15);
                    }

                    // pinjaman bisnis 6a
                    if ($val[166] != '' && $val[167] != '') {
                        $this->saveLoanImport($member->id, $val[172], $val[173], $val[166], $val[167], $val[172], 15);
                    }

                    // pinjaman bisnis 6b
                    if ($val[166] == '' && $val[167] != '') {
                        $this->saveLoanImportJasa($member->id, $val[172], $val[173], $val[170], $val[167], $val[172], 15);
                    }

                    // pinjaman ibadah umroh
                    if ($val[174] != '') {
                        $this->saveLoanImport($member->id, $val[179], $val[180], $val[174], $val[175], $val[179], 14);
                    }

                    // pinjaman perseroan
                    if ($val[181] != '') {
                        $this->saveLoanImport($member->id, $val[186], $val[187], $val[181], $val[182], $val[186], 16);
                    }

                    //simpanan pokok debit januari 1
                    if ($val[188] != '' && $val[188] != 0) {
                        $this->saveDepositImport($member->id, abs($val[188]), $val[189], $val[190], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit januari 2
                    if ($val[191] != '' && $val[191] != 0) {
                        $this->saveDepositImport($member->id, abs($val[191]), $val[192], $val[193], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit januari 1
                    if ($val[194] != '' && $val[194] != 0) {
                        $this->saveDepositImport($member->id, abs($val[194]), $val[195], $val[196], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit feb 1
                    if ($val[197] != '' && $val[197] != 0) {
                        $this->saveDepositImport($member->id, abs($val[197]), $val[198], $val[199], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit feb 2
                    if ($val[200] != '' && $val[200] != 0) {
                        $this->saveDepositImport($member->id, abs($val[200]), $val[201], $val[202], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit feb 1
                    if ($val[203] != '' && $val[203] != 0) {
                        $this->saveDepositImport($member->id, abs($val[203]), $val[204], $val[205], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit mar 1
                    if ($val[206] != '' && $val[206] != 0) {
                        $this->saveDepositImport($member->id, abs($val[206]), $val[207], $val[208], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit mar 2
                    if ($val[209] != '' && $val[209] != 0) {
                        $this->saveDepositImport($member->id, abs($val[209]), $val[210], $val[211], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit mar 1
                    if ($val[212] != '' && $val[212] != 0) {
                        $this->saveDepositImport($member->id, abs($val[212]), $val[213], $val[214], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit apr 1
                    if ($val[215] != '' && $val[215] != 0) {
                        $this->saveDepositImport($member->id, abs($val[215]), $val[216], $val[217], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit apr 2
                    if ($val[218] != '' && $val[218] != 0) {
                        $this->saveDepositImport($member->id, abs($val[218]), $val[219], $val[220], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit apr 1
                    if ($val[221] != '' && $val[221] != 0) {
                        $this->saveDepositImport($member->id, abs($val[221]), $val[222], $val[223], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit mei 1
                    if ($val[224] != '' && $val[224] != 0) {
                        $this->saveDepositImport($member->id, abs($val[224]), $val[225], $val[226], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit mei 2
                    if ($val[227] != '' && $val[227] != 0) {
                        $this->saveDepositImport($member->id, abs($val[227]), $val[228], $val[229], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit mei 1
                    if ($val[230] != '' && $val[230] != 0) {
                        $this->saveDepositImport($member->id, abs($val[230]), $val[231], $val[232], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit jun 1
                    if ($val[233] != '' && $val[233] != 0) {
                        $this->saveDepositImport($member->id, abs($val[233]), $val[234], $val[235], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit jun 2
                    if ($val[236] != '' && $val[236] != 0) {
                        $this->saveDepositImport($member->id, abs($val[236]), $val[237], $val[238], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit jun 1
                    if ($val[239] != '' && $val[239] != 0) {
                        $this->saveDepositImport($member->id, abs($val[239]), $val[240], $val[241], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit jul 1
                    if ($val[242] != '' && $val[242] != 0) {
                        $this->saveDepositImport($member->id, abs($val[242]), $val[243], $val[244], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit jul 2
                    if ($val[245] != '' && $val[245] != 0) {
                        $this->saveDepositImport($member->id, abs($val[245]), $val[246], $val[247], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit jul 1
                    if ($val[248] != '' && $val[248] != 0) {
                        $this->saveDepositImport($member->id, abs($val[248]), $val[249], $val[250], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit agus 1
                    if ($val[251] != '' && $val[251] != 0) {
                        $this->saveDepositImport($member->id, abs($val[251]), $val[252], $val[253], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit agus 2
                    if ($val[254] != '' && $val[254] != 0) {
                        $this->saveDepositImport($member->id, abs($val[254]), $val[255], $val[256], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit agus 1
                    if ($val[257] != '' && $val[257] != 0) {
                        $this->saveDepositImport($member->id, abs($val[257]), $val[258], $val[259], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit sept 1
                    if ($val[260] != '' && $val[260] != 0) {
                        $this->saveDepositImport($member->id, abs($val[260]), $val[261], $val[262], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit sept 2
                    if ($val[263] != '' && $val[263] != 0) {
                        $this->saveDepositImport($member->id, abs($val[263]), $val[264], $val[265], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit sept 1
                    if ($val[266] != '' && $val[266] != 0) {
                        $this->saveDepositImport($member->id, abs($val[266]), $val[267], $val[268], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit okto 1
                    if ($val[269] != '' && $val[269] != 0) {
                        $this->saveDepositImport($member->id, abs($val[269]), $val[270], $val[271], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit okto 2
                    if ($val[272] != '' && $val[272] != 0) {
                        $this->saveDepositImport($member->id, abs($val[272]), $val[273], $val[274], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit okto 1
                    if ($val[275] != '' && $val[275] != 0) {
                        $this->saveDepositImport($member->id, abs($val[275]), $val[276], $val[277], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit nov 1
                    if ($val[278] != '' && $val[278] != 0) {
                        $this->saveDepositImport($member->id, abs($val[278]), $val[279], $val[280], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit nov 2
                    if ($val[281] != '' && $val[281] != 0) {
                        $this->saveDepositImport($member->id, abs($val[281]), $val[282], $val[283], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit nov 1
                    if ($val[284] != '' && $val[284] != 0) {
                        $this->saveDepositImport($member->id, abs($val[284]), $val[285], $val[286], 'pokok', 1, 'credit');
                    }

                    //simpanan pokok debit des 1
                    if ($val[287] != '' && $val[287] != 0) {
                        $this->saveDepositImport($member->id, abs($val[287]), $val[288], $val[289], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok debit des 2
                    if ($val[290] != '' && $val[290] != 0) {
                        $this->saveDepositImport($member->id, abs($val[290]), $val[291], $val[292], 'pokok', 1, 'debit');
                    }

                    //simpanan pokok credit des 1
                    if ($val[293] != '' && $val[293] != 0) {
                        $this->saveDepositImport($member->id, abs($val[293]), $val[294], $val[295], 'pokok', 1, 'credit');
                    }

                    //simpanan wajib debit januari 1
                    if ($val[299] != '' && $val[299] != 0) {
                        $this->saveDepositImport($member->id, abs($val[299]), $val[300], $val[301], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit januari 2
                    if ($val[302] != '' && $val[302] != 0) {
                        $this->saveDepositImport($member->id, abs($val[302]), $val[303], $val[304], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit januari 1
                    if ($val[305] != '' && $val[305] != 0) {
                        $this->saveDepositImport($member->id, abs($val[305]), $val[306], $val[307], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit feb 1
                    if ($val[308] != '' && $val[308] != 0) {
                        $this->saveDepositImport($member->id, abs($val[308]), $val[309], $val[310], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit feb 2
                    if ($val[311] != '' && $val[311] != 0) {
                        $this->saveDepositImport($member->id, abs($val[311]), $val[312], $val[313], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit feb 1
                    if ($val[314] != '' && $val[314] != 0) {
                        $this->saveDepositImport($member->id, abs($val[314]), $val[315], $val[316], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit mar 1
                    if ($val[317] != '' && $val[317] != 0) {
                        $this->saveDepositImport($member->id, abs($val[317]), $val[318], $val[319], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit mar 2
                    if ($val[320] != '' && $val[320] != 0) {
                        $this->saveDepositImport($member->id, abs($val[320]), $val[321], $val[322], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit mar 1
                    if ($val[323] != '' && $val[323] != 0) {
                        $this->saveDepositImport($member->id, abs($val[323]), $val[324], $val[325], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit apr 1
                    if ($val[326] != '' && $val[326] != 0) {
                        $this->saveDepositImport($member->id, abs($val[326]), $val[327], $val[328], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit apr 2
                    if ($val[329] != '' && $val[329] != 0) {
                        $this->saveDepositImport($member->id, abs($val[329]), $val[330], $val[331], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit apr 1
                    if ($val[332] != '' && $val[332] != 0) {
                        $this->saveDepositImport($member->id, abs($val[332]), $val[333], $val[334], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit mei 1
                    if ($val[335] != '' && $val[335] != 0) {
                        $this->saveDepositImport($member->id, abs($val[335]), $val[336], $val[337], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit mei 2
                    if ($val[338] != '' && $val[338] != 0) {
                        $this->saveDepositImport($member->id, abs($val[338]), $val[339], $val[340], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit mei 1
                    if ($val[341] != '' && $val[341] != 0) {
                        $this->saveDepositImport($member->id, abs($val[341]), $val[342], $val[343], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit jun 1
                    if ($val[344] != '' && $val[344] != 0) {
                        $this->saveDepositImport($member->id, abs($val[344]), $val[345], $val[346], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit jun 2
                    if ($val[347] != '' && $val[347] != 0) {
                        $this->saveDepositImport($member->id, abs($val[347]), $val[348], $val[349], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit jun 1
                    if ($val[350] != '' && $val[350] != 0) {
                        $this->saveDepositImport($member->id, abs($val[350]), $val[351], $val[352], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit jul 1
                    if ($val[353] != '' && $val[353] != 0) {
                        $this->saveDepositImport($member->id, abs($val[353]), $val[354], $val[355], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit jul 2
                    if ($val[356] != '' && $val[356] != 0) {
                        $this->saveDepositImport($member->id, abs($val[356]), $val[357], $val[358], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit jul 1
                    if ($val[359] != '' && $val[359] != 0) {
                        $this->saveDepositImport($member->id, abs($val[359]), $val[360], $val[361], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit agus 1
                    if ($val[362] != '' && $val[362] != 0) {
                        $this->saveDepositImport($member->id, abs($val[362]), $val[363], $val[364], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit agus 2
                    if ($val[365] != '' && $val[365] != 0) {
                        $this->saveDepositImport($member->id, abs($val[365]), $val[366], $val[367], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit agus 1
                    if ($val[368] != '' && $val[368] != 0) {
                        $this->saveDepositImport($member->id, abs($val[368]), $val[369], $val[370], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit sept 1
                    if ($val[371] != '' && $val[371] != 0) {
                        $this->saveDepositImport($member->id, abs($val[371]), $val[372], $val[373], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit sept 2
                    if ($val[374] != '' && $val[374] != 0) {
                        $this->saveDepositImport($member->id, abs($val[374]), $val[375], $val[376], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit sept 1
                    if ($val[377] != '' && $val[377] != 0) {
                        $this->saveDepositImport($member->id, abs($val[377]), $val[378], $val[379], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit okto 1
                    if ($val[380] != '' && $val[380] != 0) {
                        $this->saveDepositImport($member->id, abs($val[380]), $val[381], $val[382], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit okto 2
                    if ($val[383] != '' && $val[383] != 0) {
                        $this->saveDepositImport($member->id, abs($val[383]), $val[384], $val[385], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit okto 1
                    if ($val[386] != '' && $val[386] != 0) {
                        $this->saveDepositImport($member->id, abs($val[386]), $val[387], $val[388], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit nov 1
                    if ($val[389] != '' && $val[389] != 0) {
                        $this->saveDepositImport($member->id, abs($val[389]), $val[390], $val[391], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit nov 2
                    if ($val[392] != '' && $val[392] != 0) {
                        $this->saveDepositImport($member->id, abs($val[392]), $val[393], $val[394], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit nov 1
                    if ($val[395] != '' && $val[395] != 0) {
                        $this->saveDepositImport($member->id, abs($val[395]), $val[396], $val[397], 'wajib', 2, 'credit');
                    }

                    //simpanan wajib debit des 1
                    if ($val[398] != '' && $val[398] != 0) {
                        $this->saveDepositImport($member->id, abs($val[398]), $val[399], $val[400], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib debit des 2
                    if ($val[401] != '' && $val[401] != 0) {
                        $this->saveDepositImport($member->id, abs($val[401]), $val[402], $val[403], 'wajib', 2, 'debit');
                    }

                    //simpanan wajib credit des 1
                    if ($val[404] != '' && $val[404] != 0) {
                        $this->saveDepositImport($member->id, abs($val[404]), $val[405], $val[406], 'wajib', 2, 'credit');
                    }

                    //simpanan sukarela debit januari 1
                    if ($val[410] != '' && $val[410] != 0) {
                        $this->saveDepositImport($member->id, abs($val[410]), $val[411], $val[412], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit januari 2
                    if ($val[413] != '' && $val[413] != 0) {
                        $this->saveDepositImport($member->id, abs($val[413]), $val[414], $val[415], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit januari 1
                    if ($val[416] != '' && $val[416] != 0) {
                        $this->saveDepositImport($member->id, abs($val[416]), $val[417], $val[418], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit januari 2
                    if ($val[419] != '' && $val[419] != 0) {
                        $this->saveDepositImport($member->id, abs($val[419]), $val[420], $val[421], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit januari 3
                    if ($val[422] != '' && $val[422] != 0) {
                        $this->saveDepositImport($member->id, abs($val[422]), $val[423], $val[421], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit feb 1
                    if ($val[425] != '' && $val[425] != 0) {
                        $this->saveDepositImport($member->id, abs($val[425]), $val[426], $val[427], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit feb 2
                    if ($val[428] != '' && $val[428] != 0) {
                        $this->saveDepositImport($member->id, abs($val[428]), $val[429], $val[430], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit feb 1
                    if ($val[431] != '' && $val[431] != 0) {
                        $this->saveDepositImport($member->id, abs($val[431]), $val[432], $val[433], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit feb 2
                    if ($val[434] != '' && $val[434] != 0) {
                        $this->saveDepositImport($member->id, abs($val[434]), $val[435], $val[436], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit feb 3
                    if ($val[437] != '' && $val[437] != 0) {
                        $this->saveDepositImport($member->id, abs($val[437]), $val[438], $val[439], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit mar 1
                    if ($val[440] != '' && $val[440] != 0) {
                        $this->saveDepositImport($member->id, abs($val[440]), $val[441], $val[442], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit mar 2
                    if ($val[443] != '' && $val[443] != 0) {
                        $this->saveDepositImport($member->id, abs($val[443]), $val[444], $val[445], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit mar 1
                    if ($val[446] != '' && $val[446] != 0) {
                        $this->saveDepositImport($member->id, abs($val[446]), $val[447], $val[448], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit mar 2
                    if ($val[449] != '' && $val[449] != 0) {
                        $this->saveDepositImport($member->id, abs($val[449]), $val[450], $val[451], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit mar 3
                    if ($val[452] != '' && $val[452] != 0) {
                        $this->saveDepositImport($member->id, abs($val[452]), $val[453], $val[454], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit apr 1
                    if ($val[455] != '' && $val[455] != 0) {
                        $this->saveDepositImport($member->id, abs($val[455]), $val[456], $val[457], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit apr 2
                    if ($val[458] != '' && $val[458] != 0) {
                        $this->saveDepositImport($member->id, abs($val[458]), $val[459], $val[460], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit apr 1
                    if ($val[461] != '' && $val[461] != 0) {
                        $this->saveDepositImport($member->id, abs($val[461]), $val[462], $val[463], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit apr 2
                    if ($val[464] != '' && $val[464] != 0) {
                        $this->saveDepositImport($member->id, abs($val[464]), $val[465], $val[466], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit apr 3
                    if ($val[467] != '' && $val[467] != 0) {
                        $this->saveDepositImport($member->id, abs($val[467]), $val[468], $val[469], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit mei 1
                    if ($val[470] != '' && $val[470] != 0) {
                        $this->saveDepositImport($member->id, abs($val[470]), $val[471], $val[472], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit mei 2
                    if ($val[473] != '' && $val[473] != 0) {
                        $this->saveDepositImport($member->id, abs($val[473]), $val[474], $val[475], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit mei 1
                    if ($val[476] != '' && $val[476] != 0) {
                        $this->saveDepositImport($member->id, abs($val[476]), $val[477], $val[478], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit mei 2
                    if ($val[479] != '' && $val[479] != 0) {
                        $this->saveDepositImport($member->id, abs($val[479]), $val[480], $val[481], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit mei 3
                    if ($val[482] != '' && $val[482] != 0) {
                        $this->saveDepositImport($member->id, abs($val[482]), $val[483], $val[484], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit jun 1
                    if ($val[485] != '' && $val[485] != 0) {
                        $this->saveDepositImport($member->id, abs($val[485]), $val[486], $val[487], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit jun 2
                    if ($val[488] != '' && $val[488] != 0) {
                        $this->saveDepositImport($member->id, abs($val[488]), $val[489], $val[490], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit jun 1
                    if ($val[491] != '' && $val[491] != 0) {
                        $this->saveDepositImport($member->id, abs($val[491]), $val[492], $val[493], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit jun 2
                    if ($val[494] != '' && $val[494] != 0) {
                        $this->saveDepositImport($member->id, abs($val[494]), $val[495], $val[496], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit jun 3
                    if ($val[497] != '' && $val[497] != 0) {
                        $this->saveDepositImport($member->id, abs($val[497]), $val[498], $val[499], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit jul 1
                    if ($val[500] != '' && $val[500] != 0) {
                        $this->saveDepositImport($member->id, abs($val[500]), $val[501], $val[502], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit jul 2
                    if ($val[503] != '' && $val[503] != 0) {
                        $this->saveDepositImport($member->id, abs($val[503]), $val[504], $val[505], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit jul 3
                    if ($val[506] != '' && $val[506] != 0) {
                        $this->saveDepositImport($member->id, abs($val[506]), $val[507], $val[508], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit jul 1
                    if ($val[509] != '' && $val[509] != 0) {
                        $this->saveDepositImport($member->id, abs($val[509]), $val[510], $val[511], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit jul 2
                    if ($val[512] != '' && $val[512] != 0) {
                        $this->saveDepositImport($member->id, abs($val[512]), $val[513], $val[514], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit jul 3
                    if ($val[515] != '' && $val[515] != 0) {
                        $this->saveDepositImport($member->id, abs($val[515]), $val[516], $val[517], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit agus 1
                    if ($val[518] != '' && $val[518] != 0) {
                        $this->saveDepositImport($member->id, abs($val[518]), $val[519], $val[520], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit agus 2
                    if ($val[521] != '' && $val[521] != 0) {
                        $this->saveDepositImport($member->id, abs($val[521]), $val[522], $val[523], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit agus 1
                    if ($val[524] != '' && $val[524] != 0) {
                        $this->saveDepositImport($member->id, abs($val[524]), $val[525], $val[526], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit agus 2
                    if ($val[527] != '' && $val[527] != 0) {
                        $this->saveDepositImport($member->id, abs($val[527]), $val[528], $val[529], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit agus 3
                    if ($val[530] != '' && $val[530] != 0) {
                        $this->saveDepositImport($member->id, abs($val[530]), $val[531], $val[532], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit sept 1
                    if ($val[533] != '' && $val[533] != 0) {
                        $this->saveDepositImport($member->id, abs($val[533]), $val[534], $val[535], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit sept 2
                    if ($val[536] != '' && $val[536] != 0) {
                        $this->saveDepositImport($member->id, abs($val[536]), $val[537], $val[538], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit sept 1
                    if ($val[539] != '' && $val[539] != 0) {
                        $this->saveDepositImport($member->id, abs($val[539]), $val[540], $val[541], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit sept 2
                    if ($val[542] != '' && $val[542] != 0) {
                        $this->saveDepositImport($member->id, abs($val[542]), $val[543], $val[544], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit sept 3
                    if ($val[545] != '' && $val[545] != 0) {
                        $this->saveDepositImport($member->id, abs($val[545]), $val[546], $val[547], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit okto 1
                    if ($val[548] != '' && $val[548] != 0) {
                        $this->saveDepositImport($member->id, abs($val[548]), $val[549], $val[550], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit okto 2
                    if ($val[551] != '' && $val[551] != 0) {
                        $this->saveDepositImport($member->id, abs($val[551]), $val[552], $val[553], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit okto 1
                    if ($val[554] != '' && $val[554] != 0) {
                        $this->saveDepositImport($member->id, abs($val[554]), $val[555], $val[556], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit okto 2
                    if ($val[557] != '' && $val[557] != 0) {
                        $this->saveDepositImport($member->id, abs($val[557]), $val[558], $val[559], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit okto 3
                    if ($val[560] != '' && $val[560] != 0) {
                        $this->saveDepositImport($member->id, abs($val[560]), $val[561], $val[562], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit nov 1
                    if ($val[563] != '' && $val[563] != 0) {
                        $this->saveDepositImport($member->id, abs($val[563]), $val[564], $val[565], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit nov 2
                    if ($val[566] != '' && $val[566] != 0) {
                        $this->saveDepositImport($member->id, abs($val[566]), $val[567], $val[568], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit nov 1
                    if ($val[569] != '' && $val[569] != 0) {
                        $this->saveDepositImport($member->id, abs($val[569]), $val[570], $val[571], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit nov 2
                    if ($val[572] != '' && $val[572] != 0) {
                        $this->saveDepositImport($member->id, abs($val[572]), $val[573], $val[574], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit nov 3
                    if ($val[575] != '' && $val[575] != 0) {
                        $this->saveDepositImport($member->id, abs($val[575]), $val[576], $val[577], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela debit des 1
                    if ($val[578] != '' && $val[578] != 0) {
                        $this->saveDepositImport($member->id, abs($val[578]), $val[579], $val[580], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela debit des 2
                    if ($val[581] != '' && $val[581] != 0) {
                        $this->saveDepositImport($member->id, abs($val[581]), $val[582], $val[583], 'sukarela', 3, 'debit');
                    }

                    //simpanan sukarela credit des 1
                    if ($val[584] != '' && $val[584] != 0) {
                        $this->saveDepositImport($member->id, abs($val[584]), $val[585], $val[586], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit des 2
                    if ($val[587] != '' && $val[587] != 0) {
                        $this->saveDepositImport($member->id, abs($val[587]), $val[588], $val[589], 'sukarela', 3, 'credit');
                    }

                    //simpanan sukarela credit des 3
                    if ($val[590] != '' && $val[590] != 0) {
                        $this->saveDepositImport($member->id, abs($val[590]), $val[591], $val[592], 'sukarela', 3, 'credit');
                    }

                    //simpanan shu debit januari 1
                    if ($val[596] != '' && $val[596] != 0) {
                        $this->saveDepositImport($member->id, abs($val[596]), $val[597], $val[598], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit januari 1
                    if ($val[599] != '' && $val[599] != 0) {
                        $this->saveDepositImport($member->id, abs($val[599]), $val[600], $val[601], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit feb 1
                    if ($val[602] != '' && $val[602] != 0) {
                        $this->saveDepositImport($member->id, abs($val[602]), $val[603], $val[604], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit feb 1
                    if ($val[605] != '' && $val[605] != 0) {
                        $this->saveDepositImport($member->id, abs($val[605]), $val[606], $val[607], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit mar 1
                    if ($val[608] != '' && $val[608] != 0) {
                        $this->saveDepositImport($member->id, abs($val[608]), $val[609], $val[610], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit mar 1
                    if ($val[611] != '' && $val[611] != 0) {
                        $this->saveDepositImport($member->id, abs($val[611]), $val[612], $val[613], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit apr 1
                    if ($val[614] != '' && $val[614] != 0) {
                        $this->saveDepositImport($member->id, abs($val[614]), $val[615], $val[616], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit apr 1
                    if ($val[617] != '' && $val[617] != 0) {
                        $this->saveDepositImport($member->id, abs($val[617]), $val[618], $val[619], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit mei 1
                    if ($val[620] != '' && $val[620] != 0) {
                        $this->saveDepositImport($member->id, abs($val[620]), $val[621], $val[622], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit mei 1
                    if ($val[623] != '' && $val[623] != 0) {
                        $this->saveDepositImport($member->id, abs($val[623]), $val[624], $val[625], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit jun 1
                    if ($val[626] != '' && $val[626] != 0) {
                        $this->saveDepositImport($member->id, abs($val[626]), $val[627], $val[628], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit jun 1
                    if ($val[629] != '' && $val[629] != 0) {
                        $this->saveDepositImport($member->id, abs($val[629]), $val[630], $val[631], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit jul 1
                    if ($val[632] != '' && $val[632] != 0) {
                        $this->saveDepositImport($member->id, abs($val[632]), $val[633], $val[634], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit jul 1
                    if ($val[635] != '' && $val[635] != 0) {
                        $this->saveDepositImport($member->id, abs($val[635]), $val[636], $val[637], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit agus 1
                    if ($val[638] != '' && $val[638] != 0) {
                        $this->saveDepositImport($member->id, abs($val[638]), $val[639], $val[640], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit agus 1
                    if ($val[641] != '' && $val[641] != 0) {
                        $this->saveDepositImport($member->id, abs($val[641]), $val[642], $val[643], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit sept 1
                    if ($val[644] != '' && $val[644] != 0) {
                        $this->saveDepositImport($member->id, abs($val[644]), $val[645], $val[646], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit sept 1
                    if ($val[647] != '' && $val[647] != 0) {
                        $this->saveDepositImport($member->id, abs($val[647]), $val[648], $val[649], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit okto 1
                    if ($val[650] != '' && $val[650] != 0) {
                        $this->saveDepositImport($member->id, abs($val[650]), $val[651], $val[652], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit okto 1
                    if ($val[653] != '' && $val[653] != 0) {
                        $this->saveDepositImport($member->id, abs($val[653]), $val[654], $val[655], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit nov 1
                    if ($val[656] != '' && $val[656] != 0) {
                        $this->saveDepositImport($member->id, abs($val[656]), $val[657], $val[658], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit nov 1
                    if ($val[659] != '' && $val[659] != 0) {
                        $this->saveDepositImport($member->id, abs($val[659]), $val[660], $val[661], 'shu', 5, 'credit');
                    }

                    //simpanan shu debit des 1
                    if ($val[662] != '' && $val[662] != 0) {
                        $this->saveDepositImport($member->id, abs($val[662]), $val[663], $val[664], 'shu', 5, 'debit');
                    }

                    //simpanan shu credit des 1
                    if ($val[665] != '' && $val[665] != 0) {
                        $this->saveDepositImport($member->id, abs($val[665]), $val[666], $val[667], 'shu', 5, 'credit');
                    }

                    #pokok 2019
                    if ($val[297] != '' && $val[297] != 0) {
                        $this->saveDepositImport($member->id, abs($val[297]), '2019-12-31', 'POKOK 2019', 'pokok', 1, 'debit');
                    }

                    #pokok 2020
//                    if ($val[296] != '' && $val[296] != 0) {
//                        $this->saveDepositImport($member->id, abs($val[296]), '2020-12-31', 'POKOK 2020', 'pokok', 1, 'debit');
//                    }

                    #wajib 2019
                    if ($val[408] != '' && $val[408] != 0) {
                        $this->saveDepositImport($member->id, abs($val[408]), '2019-12-31', 'WAJIB 2019', 'wajib', 2, 'debit');
                    }

                    #wajib 2020
//                    if ($val[407] != '' && $val[407] != 0) {
//                        $this->saveDepositImport($member->id, abs($val[407]), '2020-12-31', 'WAJIB 2020', 'wajib', 2, 'debit');
//                    }

                    #sukarela 2019
                    if ($val[594] != '' && $val[594] != 0) {
                        $this->saveDepositImport($member->id, abs($val[594]), '2019-12-31', 'SUKARELA 2019', 'sukarela', 3, 'debit');
                    }

                    #sukarela 2020
//                    if ($val[593] != '' && $val[593] != 0) {
//                        $this->saveDepositImport($member->id, abs($val[593]), '2020-12-31', 'SUKARELA 2020', 'sukarela', 3, 'debit');
//                    }

                    #shu 2019
                    if ($val[669] != '' && $val[669] != 0) {
                        $this->saveDepositImport($member->id, abs($val[669]), '2019-12-31', 'SHU DITAHAN 2019', 'shu', 5, 'debit');
                    }

                    #shu 2020
//                    if ($val[668] != '' && $val[668] != 0) {
//                        $this->saveDepositImport($member->id, abs($val[110]), '2020-12-31', 'SHU DITAHAN 2020', 'shu', 5, 'debit');
//                    }

                    #lainya 2019
                    if ($val[671] != '' && $val[671] != 0) {
                        $this->saveDepositImport($member->id, abs($val[671]), '2019-12-31', 'TOTAL LAINNYA 2019', 'lainnya', 6, 'debit');
                    }


                    if ($val[3] != '') {
                        $member_bank = new Bank();
                        $member_bank->member_id = $member->id;
                        $member_bank->bank_account_name = $val[3];
                        $member_bank->bank_account_number = $val[5];
                        $member_bank->bank_name = $val[6];
                        $member_bank->save();
                    }

                }

            }
            session()->flash('success', trans('response-message.success.create', ['object' => 'Member']));
        }catch (\Exception $exception){
	        \Log::info($exception->getMessage());
            \Log::info($exception->getFile());
            \Log::info($exception->getLine());

            session()->flash('info', 'Gagal : '. $exception->getMessage());
        }
        return redirect()->back();
	}

	public function processImport(Request $request)
	{
//		$data = CsvData::find($request->csv_data_file_id);
		ini_set('memory_limit', '64M');
		$csv_data = json_decode($request->csv_data_file_id, true);
		return $csv_data;
		foreach ($csv_data as $row) {
			$contact = new Contact();
			foreach (config('app.db_fields') as $index => $field) {
				$contact->$field = $row[$request->fields[$index]];
			}
			$contact->save();
		}

		return view('import_success');
	}

    public function getImportAdmin()
    {
        return view('import-admin');
    }

    public function parseImportAdmin(Request $request)
    {
        try {


            ini_set("memory_limit", "10056M");
            $path = $request->file('csv_file')->getRealPath();
            $csv = utf8_encode(file_get_contents($path));
            $array = explode("\n", $csv);
            $data = array_map('str_getcsv', $array);


            $csv_data = array_slice($data, 0, 600);
            foreach ($csv_data as $key => $val) {
                $region_id = null;
                if (isset($val[1])) {


                    if ($val[1] != '' || $val[1] != null) {
                        $region = Region::where('name_area', $val[1])->first();
                        $region_id = $region['id'];
                    }

                    $position = Position::where('description', strtolower($val[4]))->first();
                    $level = Level::find($position['level_id']);
                    $nick_name = str_replace(" ", "", $val[0]);
                    $user = [
                        'email' => $val[2],
                        'password' => \Hash::make($val[3]),
                        'name' => $val[0] ,
                        'username' => Str::lower($nick_name) .' '.strtolower($val[4]),
                        'position_id' => $position['id'],
                        'region_id' => $region_id,
                        'remember_token' => str_random(10),
                    ];
                }

                $user = User::create($user);
                $user->assignRole($level['name']);
            }
            session()->flash('success', trans('response-message.success.create', ['object' => 'Admin']));
        }catch (\Exception $exception){
            session()->flash('info', 'Gagal : '. $exception->getMessage());
        }

        return redirect()->back();
    }

    public function saveDepositImport($member_id, $total_deposit, $post_date, $desc, $deposit_type, $ms_deposit_id, $type)
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

        if ($type == 'debit')
        {
            $totalDepositMember = new TotalDepositMember();
            $totalDepositMember->member_id = $member_id;
            $totalDepositMember->ms_deposit_id = $ms_deposit_id;
            $totalDepositMember->value = abs($total_deposit);
            $totalDepositMember->save();
        }
    }

    public function saveLoanImport($member_id, $start, $end, $value, $service, $paydate, $loan_id){
        $from = Carbon::parse($start)->subMonth(1);
        $to = Carbon::parse($end);
        $diff_in_months = $to->diffInMonths($from);

        $cutoff = cutOff::getCutoff();
        $gte_loan1 = $to->gte($cutoff);

        $loan_value = $value * $diff_in_months;
        $loanNumber = new GlobalController();
        $loan = new TsLoans();
        $loan->loan_number = $loanNumber->getLoanNumber();
        $loan->member_id = $member_id;
        $loan->start_date = Carbon::parse($start);
        $loan->end_date = Carbon::parse($end);

        $loan->loan_id = $loan_id;
        $loan->value = $loan_value;
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

            $paydated = Carbon::parse($paydate)->addMonth($a1);
            $cutoff = cutOff::getCutoff();
            $gte_detail = $paydated->gte($cutoff);

            $loan_detail = new TsLoansDetail();
            $loan_detail->loan_id = $loan->id;
            $loan_detail->loan_number = $loan->loan_number;
            $loan_detail->value = $value;
            $loan_detail->service = $service;
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
        $loan->in_period = $in_period;
        $loan->save();
    }

    public function saveLoanImportJasa($member_id, $start, $end, $value, $service, $paydate, $loan_id){
        $from = Carbon::parse($start)->subMonth(1);
        $to = Carbon::parse($end);
        $diff_in_months = $to->diffInMonths($from);

        $cutoff = cutOff::getCutoff();
        $gteloan_jasa = $to->gte($cutoff);

        $loan_value = $value;
        $loanNumber = new GlobalController();
        $loan = new TsLoans();
        $loan->loan_number = $loanNumber->getLoanNumber();
        $loan->member_id = $member_id;
        $loan->start_date = Carbon::parse($start);
        $loan->end_date = Carbon::parse($end);

        $loan->loan_id = $loan_id;
        $loan->value = $loan_value;
        if(!$gteloan_jasa){
            $loan->approval = 'lunas';
        }else{
            $loan->approval = 'belum lunas';
        }
        $loan->period = $diff_in_months;
        $loan->save();

        $in_period = 0;
        $b2 = 1;
        for($a2 = 0; $a2 <= $diff_in_months; $a2++){

            $paydated = Carbon::parse($paydate)->addMonth($a2);
            $cutoff = cutOff::getCutoff();
            $gte_loan_detail = $paydated->gte($cutoff);

            $loan_detail = new TsLoansDetail();
            $loan_detail->loan_id = $loan->id;
            $loan_detail->loan_number = $loan->loan_number;
            $loan_detail->value = 0;
            if($a2 == $diff_in_months){
                $loan_detail->value = $value;
            }
            $loan_detail->service = $service;
            $loan_detail->pay_date = $paydated;
            $loan_detail->in_period = $b2 + $a2;

            if(!$gte_loan_detail){
                $loan_detail->approval = 'lunas';
                $in_period = $b2 + $a2;
            }else{
                $loan_detail->approval = 'belum lunas';
            }
            $loan_detail->save();
        }
        $loan->in_period = $in_period;
        $loan->save();
    }

    public function updateRoleAdmin(){
		\DB::disableQueryLog();
        $Csv = new \App\Helpers\CsvToArray();
        $file = base_path() . '/database/seeds/csv/update_admin_roles_real-2.csv';
        ini_set("memory_limit", "10056M");
        $csv = utf8_encode(file_get_contents($file));
        $array = explode("\r", $csv);
        $csv_data = array_map('str_getcsv', $array);

		// dd($csv_data);
        foreach ($csv_data as $key => $val) {
			// dd($val);
			// dd($val);
			if($val[1] != ''){
				$validator = \Validator::make(['email' => $val[1]],[
					'email' => 'required|email'
				]);

				if($validator->passes()){
					$email = $val[1];
				}else{
					$email = $val[1] . '@gmail.com';

				}

				$getPosition = Position::where('description', strtolower($val[0]))->first();
				$user = User::where('email', $email)->first();
				$region = Region::where('name_area', trim($val[3]))->first();
				if(!empty($user) && !empty($getPosition))
				{
					$updatePosition = Member::where('user_id',$user->id)->first();
					$updatePosition->position_id = $getPosition->id;
					$updatePosition->save();
					$user->region_id = $region->id;
					$user->save();
					$user->assignRole($getPosition->level->name);
				}
			}

        }




        return 'updated';
    }

}
