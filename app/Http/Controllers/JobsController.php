<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GeneralSetting;
use App\ConfigDepositMembers;
use App\DepositTransaction;
use App\DepositTransactionDetail;
use App\Member;
use DB;
class JobsController extends Controller
{
	//

	public function generate_deposit(){
		$cutOff = GeneralSetting::select('content')->where('name', '=', 'cut-off')->first();
		if(!empty($cutOff)){
			$members = Member::where('is_active', '=', 1)->where('verified_at', '<>', '')->get();
			$totaldeposit = 0;
			foreach ($members as $member) {

                $ConfigDepositMembers = ConfigDepositMembers::where('member_id', '=', $member->id);
                $total = ConfigDepositMembers::where('member_id', '=', $member->id)->select(DB::raw('sum(value) as total'))->groupBy('member_id')->get();
                $getMemberConfigDeposit = $ConfigDepositMembers->get();

                if($ConfigDepositMembers->count() > 0){
                    $ts_deposits                = new DepositTransaction();
                    $ts_deposits->member_id     = $member->id;
                    $ts_deposits->ms_deposit_id = 1;
                    $ts_deposits->deposit_number = rand();
                    $ts_deposits->total_deposit = $total[0]['total'];
                    $ts_deposits->post_date     = new \DateTime();
                    $ts_deposits->save();

                    foreach ($getMemberConfigDeposit as $depositMember) {
                        // return $depositMember->type;
                        $ts_deposit_detail                  = new DepositTransactionDetail();
                        $ts_deposit_detail->transaction_id  = $ts_deposits->id;
                        $ts_deposit_detail->deposits_type   = $depositMember->type;
                        $ts_deposit_detail->debit           = 0;
                        $ts_deposit_detail->credit          = $depositMember->value;
                        $ts_deposit_detail->total           = $depositMember->value;
                        $ts_deposit_detail->save();
                    }
                }
			}
		}
	}
}
