<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TsLogsDeposit extends Model
{
	protected $table='ts_logs_deposit_histories';
	
    public function member()
	{
		return $this->belongsTo(Member::class, 'member_id');
	}
}
