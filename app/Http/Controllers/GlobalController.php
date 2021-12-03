<?php

namespace App\Http\Controllers;

use App\TsDeposits;
use \Crypt;
use App\Member;
use App\TsLoans;
use App\Resign;
use Carbon\Carbon;
use App\TsLogsDeposit;
use App\TsLoansDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GlobalController extends Controller
{
    public function getKeysArr($dataArray='')
    {
    	$i   	    = 0;
    	$key_ready  = array();
    	foreach ($dataArray as $key => $value) {
    	  if(!$value[$i] || $value[$i] == null ){
		  	  continue;
		    } else {
		      $key_ready [] = $key;
		    }
    	}
		return $key_ready;
    }
    public function tableName($table='')
    {
    	return $table->getTable();
    }
    public function idr($el='')
    {
        return 'Rp.'. number_format($el, 2, ',', '.');
    }
     public function encrypter($el)
    {
        if ($el == '') {
            return '';
        }
        return Crypt::encrypt($el);
    }

    public function decrypter($el)
    {
        if ($el == '') {
            return '';
        }
        return Crypt::decrypt($el);
    }
    public function logDeposit($name, $member_id, $in=null, $out=null, $last=null)
    {
        $log_deposit                      = new TsLogsDeposit();
        $log_deposit->id                  = $log_deposit::max('id')+1;
        $log_deposit->member_id           = $member_id;
        $log_deposit->name                = $name;
        $log_deposit->nominal_in          = $in;
        $log_deposit->nominal_out         = $out;
        $log_deposit->last_nominal        = $last;
        $log_deposit->date                = Carbon::now()->format('Y-m-d');;
        $log_deposit->save();
        return 'Success';
    }
    public function revive($el='')
    {
        if ($el == '') {
            return '';
        }
        return str_replace('.', '', $el);
    }
    public function dateTime($el='', $act='')
    {
        if($el === null){
            $asYouWish = null;
        }else{
            $toDay    = Carbon::parse($el);
            switch ($act) {
                case 'date':
                    $asYouWish = $toDay->format('Y-m-d');
                    break;
                case 'day':
                    $asYouWish = $toDay->format('d');
                    break;
                case 'month':
                    $asYouWish = $toDay->format('m');
                    break;
                case 'year':
                    $asYouWish = $toDay->format('Y');
                    break;
                case 'dayName':
                    $asYouWish = $toDay->format('l');
                    break;
                case 'indo':
                    $asYouWish = $toDay->format('d-M-Y');
                    break;

                default:
                    $asYouWish = $toDay;
                    break;
            }
        }

        return $asYouWish;
    }
    public function getLoanNumber()
    {
        $prefix = 'P';
        $loanMax = TsLoans::orderBy('loan_number', 'DESC')->first();

        $carbon = Carbon::now();
        $year = $carbon->format('Y');
        $month = $carbon->format('m');

        if($loanMax == null || $loanMax->loan_number == null){
            $lastCode = $prefix."-$year-$month-000000";
        }else{
            $lastCode = $loanMax->loan_number;
        }

        $explode = explode('-', $lastCode);
        if ((int)$year > (int)$explode[1]) {
            $sortNumber = '000001';
        } else {
            if ($month > $explode[2]) {
                $sortNumber = '000001';
            } else {
                $month = $explode[2];
                $sortNumber = str_pad($explode[3] + 1, 6, 0, STR_PAD_LEFT);
            }
        }
        return $prefix . '-' . $year . '-' . $month . '-' . $sortNumber;

    }

    public function getBspNumber()
    {
        $prefix = 'KSBSP';
        $member  = Member::orderBy('nik_koperasi', 'DESC')->first();
        $carbon = Carbon::now();
        $year = $carbon->format('Y');
        if($member == null || $member->nik_koperasi == null){
            $lastCode = $prefix."-$year-000000";
        }else{
            $lastCode = $member->nik_koperasi;
        }


        $explode = explode('-', $lastCode);
        if ((int)$year > (int)$explode[1]) {
            $sortNumber = '000001';
        } else {
            $sortNumber = str_pad($explode[2] + 1, 6, 0, STR_PAD_LEFT);
        }


        return $prefix . '-' . $year . '-' . $sortNumber;

    }

    public function getDepositNumber()
    {
        $toDate = $this->dateTime(now(), 'full');
        $prefix = 'D';
        $deposit = TsDeposits::orderBy('deposit_number', 'DESC')->first();
        $carbon = Carbon::now();
        $year = $carbon->format('Y');
        $month = $carbon->format('m');

        if($deposit == null || $deposit->deposit_number == null || empty($deposit)){
            $lastCode = $prefix."-$year-$month-000000";
        }else{
            $lastCode = $deposit->deposit_number;
        }

        $explode = explode('-', $lastCode);
        if ((int)$year > (int)$explode[1]) {
            $sortNumber = '000001';
        } else {
            if ($month > $explode[2]) {
                $sortNumber = '000001';
            } else {
                $month = $explode[2];
                $sortNumber = str_pad($explode[3] + 1, 6, 0, STR_PAD_LEFT);
            }
        }
        return $prefix . '-' . $year . '-' . $month . '-' . $sortNumber;
    }

    public function checkLoans($member_id='')
    {
        $get   = TsLoans::where(['status' => 'belum lunas', 'member_id' => $member_id]);
        if($get->count() > 0){
            return true;
        }
        return false;
    }
    public function checkRsn($member_id='')
    {
        $get   = Resign::where(['member_id' => $member_id])->whereIn('approval', array('approved1', 'approved2', 'waiting'));
        if($get->count() > 0){
            return true;
        }
        return false;
    }
    public function close($member_id='')
    {
        $depH   = TsLogsDeposit::where(['member_id' => $member_id])->orderBy('id', 'DESC');
        if($depH->count() > 0){
            $get   = TsLoans::where(['approval' => 'belum lunas', 'member_id' => $member_id]);
            $count = 0;
            if($get->count() > 0){
                foreach ($get->get() as $e) {
                    $detail = TsLoansDetail::selectRaw('sum(value) as ttl')->where(['approval' => 'belum lunas', 'loan_id' => $e->id])->first();
                    $count  += $detail->ttl;
                }
                if($count > $depH->first()->last_nominal){
                return true;
                }
            }
                return false;
        } else{
            $get   = TsLoans::where(['approval' => 'belum lunas', 'member_id' => $member_id]);
             if($get->count() > 0){
                 return true;
             }
             return false;
        }
    }
}
