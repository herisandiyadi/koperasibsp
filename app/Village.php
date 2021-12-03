<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'villages';

    public function district(){
        return $this->belongsTo(District::class);
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
