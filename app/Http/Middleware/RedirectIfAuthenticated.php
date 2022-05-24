<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next, ?string ...$guards): Response
	{
		$guards = empty($guards) ? [null] : $guards;

		foreach ($guards as $guard) {
			if (auth($guard)->check()) {
				return redirect()->route('home');
			}
		}

		return $next($request);
	}
}
