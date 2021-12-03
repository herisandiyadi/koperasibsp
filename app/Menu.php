<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table='ms_menus';
    public function child()
    {
        return $this->belongsTo(Menu::class, 'child_id');
    }
}
