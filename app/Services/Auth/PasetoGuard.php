<?php

namespace App\Services\Auth;

use App\Models\TokenBlacklist;
use Illuminate\Auth\GuardHelpers;
use App\Exceptions\TokenBlacklisted;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class PasetoGuard implements Guard
{
	use GuardHelpers;

	public function __construct(UserProvider $provider)
	{
		$this->setProvider($provider);
	}

	/**
	 * Get the currently authenticated user.
	 */
	public function user(): ?Authenticatable
	{
		if ($this->hasUser() && !app()->runningUnitTests()) {
			return $this->user;
		}

		$decodedToken = $this->getTokenPayload();

		if (!$decodedToken) {
			return null;
		}

		$this->user = $this->getProvider()->retrieveById($decodedToken['jti']);

		return $this->user;
	}

	public function validate(array $credentials = []): bool
	{
		return !empty($this->attempt($credentials));
	}

	public function attempt(array $credentials = []): ?Authenticatable
	{
		$provider = $this->getProvider();

		$this->user = $provider->retrieveByCredentials($credentials);
		$this->user = $this->user && $provider->validateCredentials($this->user, $credentials) ? $this->user : null;

		return $this->user;
	}

	public function getTokenPayload(): ?array
	{
		$token = $this->getTokenFromRequest();

		if (!$token) {
			return null;
		}

		if (TokenBlacklist::where('token', $token)->exists()) {
			throw new TokenBlacklisted(__('auth.token-blacklisted'), 403);
		}

		$paseto = new Paseto;

		return $paseto->decodeToken($token)->getClaims();
	}

	public function logout(): bool
	{
		if (!$this->hasUser() || !($token = $this->getTokenFromRequest())) {
			return false;
		}

		$this->user->blacklistedTokens()->create([
			'token' => $token
		]);

		return true;
	}

	protected function getTokenFromRequest(): ?string
	{
		$request = request();

		return $request->bearerToken() ?? $request->token;
	}
}
