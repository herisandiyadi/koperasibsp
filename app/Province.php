<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table='provinces';
    public function city()
    {
        return $this->hasMany(City::class);
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
