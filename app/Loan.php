<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Loan extends Model
{
	protected $table='ms_loans';
	public function ts_loans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TsLoans::class);
	}

	public function scopePublish($query){
	    return $query->where('publish', 1);
    }

    public function scopefByTopDeposit(){
        return $this->ts_loans()->getbyArea;
    }
}
