<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;
    protected $table = 'ms_regions';

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function project()
    {
        return $this->hasMany(Project::class);
	}

	public function branch()
    {
        return $this->hasMany(Branch::class);
    }

    public function member(){
        return $this->hasMany(Member::class);
    }
}
