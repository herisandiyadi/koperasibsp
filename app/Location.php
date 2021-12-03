<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
	// use SoftDeletes;
	protected $table = 'ms_locations';
	public function province()
	{
		return $this->belongsTo('App\Province');
	}
	public function city()
	{
		return $this->belongsTo('App\City');
	}
	public function district()
	{
		return $this->belongsTo('App\District');
	}
	public function village()
	{
		return $this->belongsTo('App\Village');
	}
	public function project()
	{
		return $this->belongsTo(Project::class);
	}
}
