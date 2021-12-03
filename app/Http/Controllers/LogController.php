<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class LogController extends Controller
{
    public function logout(){
        $getUser = User::count();
        if($getUser > 0) {
            if(auth()->check()) {
                // if ($this->getSession('user_detail')->type != 'super_admin') {
                //     Session::flush();
                //     Auth::logout();
                //     return redirect('login');
                // } else {
                //     Session::flush();
                //     Auth::logout();
                //     return redirect('login-admin');
                // }
                Auth::logout();
                return redirect('login');
            }else{
                return redirect('login');
            }
        }
    }
}