<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;

class EnsureEmailIsVerified
{
	use ApiResponser;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::guest()) {
			return $this->respondUnauthorized();
		}

		if (!Auth::user()->verified) {
			return $this->respondForbidden('Your email address is not verified.');
		}

		return $next($request);
	}
}
