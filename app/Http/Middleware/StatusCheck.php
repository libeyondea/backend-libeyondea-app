<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class StatusCheck
{
	use ApiResponser;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next, $status)
	{
		if (Auth::guest()) {
			return $this->respondUnauthorized();
		}

		if ($status === 'active') {
			if (!Auth::user()->status) {
				return $this->respondForbidden('Your account has not been activated.');
			}
		} else if ($status === 'deactive') {
			if (Auth::user()->status) {
				return $this->respondForbidden('Your account has been activated.');
			}
		}

		return $next($request);
	}
}
