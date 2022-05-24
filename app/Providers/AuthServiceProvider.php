<?php

namespace App\Providers;

use App\Services\Auth\PasetoGuard;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		$this->registerPolicies();

		auth()->extend('paseto', function (Application $app, string $name, array $config): PasetoGuard {
			return new PasetoGuard(auth()->createUserProvider($config['provider']));
		});
	}
}
