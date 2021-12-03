<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigDepositMembers extends Model
{
    protected $table = 'config_deposit_member';
	protected $fillable = ['member_id', 'type', 'value'];

	public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function scopeFPokok($q)
	{
		return $q->where('type','pokok');
	}
}
