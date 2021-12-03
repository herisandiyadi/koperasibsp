<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TestController extends Controller
{
    //
	public function shu(Request $request){


		$input = Input::all();
		return $input;
	}
}
