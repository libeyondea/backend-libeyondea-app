<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponsor;

class StatusCheck
{
	use ApiResponsor;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
	 * @param  string  $status
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next, $status)
	{
		if (auth()->guest()) {
			return $this->responseUnauthorized();
		}

		if ($status === 'active') {
			if (auth()->user()->status !== 1) {
				return $this->responseForbidden('Your account has not been activated.');
			}
		} elseif ($status === 'inactive') {
			if (auth()->user()->status !== 0) {
				return $this->responseForbidden('Your account has not been inactivated.');
			}
		} elseif ($status === 'blocked') {
			if (auth()->user()->status !== 2) {
				return $this->responseForbidden('Your account has not been blocked.');
			}
		}

		return $next($request);
	}
}
