<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenerateRekapAnggota extends Model
{
    protected $table = 'generate_rekap_anggota';
    protected $fillable = ['name','start','end','status','attachment'];
}
