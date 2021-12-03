<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TsLoansDetail extends Model
{
	protected $table='ts_loan_details';

	public function loan()
	{
		return $this->belongsTo(TsLoans::class, 'loan_id');
	}
}
