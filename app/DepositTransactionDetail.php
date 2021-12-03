<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepositTransactionDetail extends Model
{
	protected $table = 'ts_deposit_details';
	protected $casts = [
		'total' => 'decimal',
		'debit' => 'decimal',
		'credit' => 'decimal',
	];

	public function transaction()
	{
		return $this->belongsTo(DepositTransaction::class, 'transaction_id');
	}
}
