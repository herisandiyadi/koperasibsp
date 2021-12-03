<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;

class MemberOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->isMember()) {

            if($request->expectsJson())
            {
                return ResponseHelper::json('', 403, trans('response-message.unauthorized.visit'));
            }
            abort(403, trans('response-message.unauthorized.visit'));
        }
        return $next($request);
    }
}
