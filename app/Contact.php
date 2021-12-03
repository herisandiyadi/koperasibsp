<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Matrix\Builder;

class Contact extends Model
{
    //
    protected $table = 'contacts';
    protected $fillable = [
        'from_id',
        'to_id',
        'judul',
        'pesan',
        'attachment'
    ];

    public function fromId()
    {
        return $this->belongsTo(User::class, 'from_id', 'id');
    }

    public function toId()
    {
        return $this->belongsTo(User::class, 'to_id', 'id');
    }

    public function scopeFByUser($query, $id)
    {
        return $query->where('from_id', $id)->orWhere('to_id', $id);
    }
}
