<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

	protected $table='ms_branchs';
    protected $fillable = ['branch_code','region_id','branch_name','telp','address','status'];

	public function region()
	{
		return $this->belongsTo('App\Region');
	}
}
