<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $table='privileges';
    protected $casts = [
        'view' => 'boolean',
        'edit'=> 'boolean',
        'create'=> 'boolean',
        'delete'=> 'boolean'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function isViewAllowed()
    {
        return $this->view;
    }

    public function isEditAllowed()
    {
        return $this->edit;
    }

    public function isCreateAllowed()
    {
        return $this->create;
    }

    public function isDeleteAllowed()
    {
        return $this->delete;
    }
}
