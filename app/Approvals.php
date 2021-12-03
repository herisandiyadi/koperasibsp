<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Approvals extends Model
{
    protected $table = 'approvals';
    protected $fillable = [
        'fk',
        'user_id',
        'model',
        'approval',
        'is_approve',
        'is_reject',
        'is_revision',
        'is_waiting',
        'status',
        'note'
    ];

    protected $casts = [
      'approval' => 'json'
    ];

    public function ts_loans(){
        return $this->belongsTo(TsLoans::class, 'fk');
    }

    public function resign(){
        return $this->belongsTo(Resign::class, 'fk');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
