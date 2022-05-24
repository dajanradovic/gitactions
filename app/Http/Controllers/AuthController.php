<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Auth\OAuthHandler;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
	public function silent(User $user): RedirectResponse
	{
		if (!$user->isAvailable()) {
			abort(403);
		}

		if (auth()->check()) {
			auth()->logout();
		}

		auth()->login($user);

		$user->fireLoginEvent();

		return redirect()->intended(route('home'));
	}

	public function oAuthRedirect(string $driver): RedirectResponse
	{
		$oAuthHandler = new OAuthHandler;
		$redirect = $oAuthHandler->getRedirect($driver);

		if (!$redirect) {
			abort(401, $oAuthHandler->getLastExceptionMessage());
		}

		return $redirect;
	}

	public function oAuthCallback(string $driver): RedirectResponse
	{
		$oAuthHandler = new OAuthHandler;
		$user = $oAuthHandler->getUser($driver);

		if (!$user) {
			abort(401, $oAuthHandler->getLastExceptionMessage());
		}

		$check = User::where(function ($query) use ($driver, $user): void {
			$query->where('email', $user->getEmail())->orWhere($driver, $user->getId());
		})
		->first();

		if ($check) {
			$check->update([
				$driver => $user->getId(),
				'avatar' => $user->getAvatar(),
			]);

			if (auth()->check()) {
				auth()->logout();
			}

			auth()->login($check);

			$check->fireLoginEvent();
		}

		return auth()->check()
			? redirect()->intended(route('home'))
			: redirect()->route('register')->with([
				'oauth_user' => $user,
				'oauth_driver' => $driver
			]);
	}
}
