<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TsDepositsDetail extends Model
{
    protected $table='ts_deposit_details';

	public function deposit()
	{
		return $this->belongsTo(TsDeposits::class, 'transaction_id');
	}
}
