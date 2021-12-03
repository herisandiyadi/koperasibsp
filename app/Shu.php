<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shu extends Model
{
    //

	protected $table = 'ms_shu_settings';
	protected $fillable = ['is_complete'];
	public function isComplete()
	{
		return $this->is_complete == 1;
	}

}
