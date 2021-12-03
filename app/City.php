<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->hasMany(District::class);
    }
    public function region()
    {
        return $this->hasMany(Project::class);
    }
    public function location()
    {
        return $this->hasMany(Location::class);
    }
}
