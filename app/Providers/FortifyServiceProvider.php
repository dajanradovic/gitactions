<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\RateLimiter;

class FortifyServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		Fortify::createUsersUsing(CreateNewUser::class);
		Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

		Fortify::authenticateUsing(function (Request $request): ?User {
			$user = User::loginLogic($request)->canViewRouteQuery('home')->where(config('fortify.email', 'email'), $request->email)->first();

			return $user && Hash::check($request->password, $user->password) ? $user : null;
		});

		Fortify::loginView(function (): View {
			return view('auth.login');
		});

		Fortify::registerView(function (): View {
			if (!setting('registration_active')) {
				abort(403);
			}

			return view('auth.register', [
				'oauth_driver' => session('oauth_driver'),
				'oauth_user' => session('oauth_user')
			]);
		});

		Fortify::requestPasswordResetLinkView(function (): View {
			return view('auth.passwords.email');
		});

		Fortify::resetPasswordView(function (Request $request): View {
			return view('auth.passwords.reset', ['request' => $request]);
		});

		Fortify::verifyEmailView(function (): View {
			return view('auth.verify');
		});

		Fortify::confirmPasswordView(function (): View {
			return view('auth.passwords.confirm');
		});

		Fortify::twoFactorChallengeView(function (): View {
			return view('auth.2fa');
		});

		RateLimiter::for('login', function (Request $request): Limit {
			$email = (string) $request->email;

			return new Limit($email . $request->ip(), setting('login_max_attempts'), setting('login_backoff_minutes'));
		});

		RateLimiter::for('two-factor', function (Request $request): Limit {
			return new Limit($request->session()->get('login.id'), setting('login_max_attempts'), setting('login_backoff_minutes'));
		});
	}
}
