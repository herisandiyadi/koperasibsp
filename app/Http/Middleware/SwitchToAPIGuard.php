<?php

namespace App\Http\Middleware;

use Closure;

class SwitchToAPIGuard
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
        if (auth()->getDefaultDriver() == 'web') {

            auth()->setDefaultDriver('api');
        }
        return $next($request);
    }
}
