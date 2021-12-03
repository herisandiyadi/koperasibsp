<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
	}
//
//	public function handle($request, \Closure $next, ...$guards)
//    {
//        if($request->expectsJson())
//        {
//            try {
//                auth('api')->userOrFail();
//                return $next($request);
//            } catch (UserNotDefinedException $e) {
//                return ResponseHelper::json('',401, trans('response-message.unauthorized.session'));
//            }
//        }
//        return $next($request);
//    }
}
