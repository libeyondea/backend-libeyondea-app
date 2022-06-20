<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponser;

class RoleCheck
{
	use ApiResponser;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle($request, Closure $next, $role)
	{
		if (Auth::guest()) {
			return $this->respondUnauthorized();
		}

		$roles = is_array($role) ? $role : explode('|', $role);

		if (!collect($roles)->contains(Auth::user()->role)) {
			return $this->respondForbidden();
		}

		return $next($request);
	}
}
