<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponsor;

class StatusCheck
{
	use ApiResponsor;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param  string  $status
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next, $status)
	{
		if (Auth::guest()) {
			return $this->responseUnauthorized();
		}

		if ($status === 'active') {
			if (Auth::user()->status !== 1) {
				return $this->responseForbidden('Your account has not been activated.');
			}
		} elseif ($status === 'deactive') {
			if (Auth::user()->status !== 0) {
				return $this->responseForbidden('Your account has been activated.');
			}
		} elseif ($status === 'blocked') {
			if (Auth::user()->status !== 2) {
				return $this->responseForbidden('Your account has been blocked.');
			}
		}

		return $next($request);
	}
}
