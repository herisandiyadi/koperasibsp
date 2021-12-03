<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\ResponseHelper;

class SuperOnly
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
		if (!auth()->user()->isMember() || auth()->user()->isSu()) {
            return $next($request);
		}
        if ($request->wantsJson()) {
            return ResponseHelper::json('', 403, trans('response-message.unauthorized.feature'));
        }
        session()->flash('notice', trans('response-message.unauthorized.feature'));
        return redirect('dashboard');

	}
}
