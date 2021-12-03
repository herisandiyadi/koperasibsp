<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table='districts';
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function village()
    {
        return $this->hasMany(Village::class);
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
