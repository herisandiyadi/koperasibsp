<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositTransaction extends Model
{
	protected $table = 'ts_deposits';
	protected $dates = ['post_date'];
	protected $casts = [
		'total_deposit' => 'decimal',
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public function deposit()
	{
		return $this->belongsTo(Deposit::class,'ms_deposit_id');
	}

	public function detail()
	{
		return $this->hasMany(DepositTransactionDetail::class,'transaction_id');
	}

	public static function totalDeposit($id){
        return DepositTransaction::where('member_id', $id)->sum('total_deposit');
	}

	public function totalDepositWajib(){
		return $this->hasOne(DepositTransactionDetail::class,'transaction_id')->where('ts_deposit_details.deposits_type', '=', 'wajib')->selectRaw('ts_deposit_details.transaction_id, sum(ts_deposit_details.total) as totalwajib')
			->groupBy('ts_deposit_details.transaction_id');
	}

    public static function getDepositArea($region){
        $selected = self::where('status', 'paid')->sum('total_deposit');
        if(!empty($region)){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->where('status', 'paid')->sum('total_deposit');
        }


        return $selected;
    }
}
