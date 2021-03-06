<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * The path to the "home" route for your application.
	 *
	 * This is used by Laravel authentication to redirect users after login.
	 *
	 * @var string
	 */
	public const HOME = '/';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 */
	public function boot(): void
	{
		$this->configureRateLimiting();

		$this->routes(function (): void {
			Route::prefix('api')
				->middleware('api')
				->group(base_path('routes/api.php'));

			Route::middleware('web')
				->group(base_path('routes/web.php'));
		});
	}

	/**
	 * Configure the rate limiters for the application.
	 */
	protected function configureRateLimiting(): void
	{
		RateLimiter::for('api', function (Request $request): Limit {
			$user = getUser();
			$role = $user->role ?? null;

			return new Limit($user->id ?? $request->ip(), $role->api_rate_limit ?? setting('api_rate_limit'), $role->api_rate_limit_backoff_minutes ?? setting('api_rate_limit_backoff_minutes'));
		});
	}
}
