<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PencairanSimpanan extends Model
{
	protected  $table = 'pencairan_simpanan';
    protected $fillable = [
		'member_id',
		'bank_id',
		'date',
		'phone',
		'jumlah',
		'approval'
	];

	public function member()
	{
		return $this->belongsTo(Member::class);
	}

	public static function getPencairanSimpananArea($region){
        $selected = self::all();
        if(!empty($region)){
            $selected = self::whereHas('member', function ($query) {
                return $query->where('region_id', '=', auth()->user()->region['id']);
            })->get();
        }


        return $selected;
    }
}
