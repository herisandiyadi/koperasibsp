<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TsLogsLoan extends Model
{
	protected $table='ts_logs_loan_histories';

    public function member()
	{
		return $this->belongsTo(Member::class, 'member_id');
	}
}
