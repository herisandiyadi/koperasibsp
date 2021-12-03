<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use SoftDeletes;
	protected $table = 'ms_deposits';
	protected $fillable = ['deposit_name', 'deposit_minimal', 'deposit_maximal'];
	protected $casts = [
	    'deposit_minimal'=> 'integer',
	    'deposit_maximal'=> 'integer',
    ];

	public function transaction()
    {
        return $this->hasMany(DepositTransaction::class, 'ms_deposit_id');
    }
}
