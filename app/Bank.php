<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;
    protected $table = 'ms_banks';
    protected $fillable = ['member_id','bank_name','bank_account_name','bank_account_number','bank_branch'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
