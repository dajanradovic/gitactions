<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRolePermissions
{
	/**
	 * Handle an incoming request.
	 */
	public function handle(Request $request, Closure $next): Response
	{
		$user = $request->user();
		$route = $request->route()->getName();

		if (
			!$user
			|| !$user->isAvailable()
			|| !$user->hasAllowedMethod($request->method())
			|| ($route && !$user->canViewRoute($route))
		) {
			abort(Response::HTTP_FORBIDDEN);
		}

		$dryRunHeaderName = config('custom.dry_run_header_name');

		if ($header = $request->header($dryRunHeaderName) && !$this->checkIfMiddlewareExistsInStack('restrict_by_user_type', $request->route()->gatherMiddleware())) {
			abort(Response::HTTP_NO_CONTENT, '', [
				$dryRunHeaderName => $header
			]);
		}

		return $next($request);
	}

	protected function checkIfMiddlewareExistsInStack(string $middleware, array $middlewareStack): bool
	{
		foreach ($middlewareStack as $currMiddleware) {
			if (str_starts_with($currMiddleware, $middleware)) {
				return true;
			}
		}

		return false;
	}
}
