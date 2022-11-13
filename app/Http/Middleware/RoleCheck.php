<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class RoleCheck
{
	use ApiResponser;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @param  string[]|string  $roles
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next, $roles)
	{
		if (Auth::guest()) {
			return $this->respondUnauthorized();
		}

		$roles = is_array($roles) ? $roles : explode('|', $roles);

		if (!collect($roles)->contains(Auth::user()->role)) {
			return $this->respondForbidden();
		}

		return $next($request);
	}
}
