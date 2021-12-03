<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approveman extends Model
{

    protected $fillable = [
        'id',
        'name',
        'position_id',
        'email',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
}
