<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    //
    protected $table = 'general_settings';
    protected $fillable = ['name', 'content'];
    public static function getCutOffDate()
    {
        // return self::where('name', '=', 'cut-off')->first()->content;
    }
}
