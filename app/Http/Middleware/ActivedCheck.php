<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class ActivedCheck
{
	use ApiResponser;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::guest()) {
			return $this->respondUnauthorized();
		}

		if (!Auth::user()->actived) {
			return $this->respondForbidden('Your account is not actived.');
		}

		return $next($request);
	}
}
