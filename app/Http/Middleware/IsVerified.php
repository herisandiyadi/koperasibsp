<?php

namespace App\Http\Middleware;

use Auth;
use App\user;
use Closure;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\ValidationException;
class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->isMember())
        {
            if(auth()->check() && !auth()->user()->isSu() && !auth()->user()->isMemberVerified()) {
                \Session::flash('error', 'Tolong, verifikasi email anda terlebih dahulu.');
                Auth::logout();
                return \redirect('login');
            } else if(!auth()->user()->isSu() && auth()->user()->member->is_active == false){
                \Session::flash('error', 'Maaf, akun anda belum diaktivasi. Mohon hubungi bagian administrasi untuk info lebih lanjut.');
                Auth::logout();
                return \redirect('login');
            } else if(auth()->user()->is_staff() == false){
                \Session::flash('error', 'Maaf, informasi staff tidak tersedia. Mohon hubungi bagian administrasi untuk info lebih lanjut.');
                Auth::logout();
                return \redirect('login');

            }
        }
        return $next($request);
    }
}
