<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
	/**
	 * Handle an incoming request.
	 */
	public function handle($request, Closure $next, ...$guards): Response
	{
		try {
			$this->authenticate($request, empty($guards) ? array_keys(config('auth.guards')) : $guards);
		} catch (AuthenticationException $e) {
			if (!$request->wantsJson()) {
				throw $e;
			}

			if ($response = $this->tryBasicAuth()) {
				return $response;
			}
		} catch (Exception $e) {
			abort($e->getCode() == Response::HTTP_FORBIDDEN ? Response::HTTP_FORBIDDEN : Response::HTTP_UNAUTHORIZED, $e->getMessage());
		}

		return $next($request);
	}

	protected function tryBasicAuth(): ?Response
	{
		return $this->auth->onceBasic(setting('basic_auth_username_field'));
	}
}
