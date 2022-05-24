<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLocale
{
	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$user = $request->user();

		if ($user) {
			app()->setLocale($user->locale);
		}

		return $next($request);
	}
}
