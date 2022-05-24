<?php

namespace App\Services\Auth;

use Exception;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class OAuthHandler
{
	protected ?string $lastExceptionMessage = null;

	public function getLastExceptionMessage(): ?string
	{
		return $this->lastExceptionMessage;
	}

	public function getRedirect(string $driver, bool $stateless = false): ?RedirectResponse
	{
		if (!setting($driver . '_active')) {
			return null;
		}

		$this->setOAuthConfig($driver);

		return $this->getSocialite($driver, $stateless)->redirect();
	}

	public function getUser(string $driver, ?string $token = null, bool $stateless = false): ?User
	{
		$this->lastExceptionMessage = null;

		if (!setting($driver . '_active')) {
			return null;
		}

		if (!$token) {
			$this->setOAuthConfig($driver);
		}

		try {
			$socialite = $this->getSocialite($driver, $stateless);

			return $token ? $socialite->userFromToken($token) : $socialite->user();
		} catch (Exception $e) {
			$this->lastExceptionMessage = $e->getMessage();
		}

		return null;
	}

	protected function setOAuthConfig(string $driver): self
	{
		config([
			'services.' . $driver . '.redirect' => route('login.oauth.callback', $driver),
			'services.' . $driver . '.client_id' => setting($driver . '_client_id'),
			'services.' . $driver . '.client_secret' => setting($driver . '_client_secret')
		]);

		return $this;
	}

	protected function getSocialite(string $driver, bool $stateless = false): AbstractProvider
	{
		$socialite = Socialite::driver($driver);

		return $stateless ? $socialite->stateless() : $socialite;
	}
}
