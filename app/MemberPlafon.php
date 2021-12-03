<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberPlafon extends Model
{
	protected $table = 'member_plafons';

	public function member()
	{
		return $this->belongsTo(Member::class, 'member_id');
	}

	public function scopeFNoSuper()
    {
        return $this->member()->whereHas('user', function($q){
            $q->fNoSuper();
        });
    }

    public static function getPlafonArea($region){
        $selected = self::all();
        if(!empty($region)){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }
}
