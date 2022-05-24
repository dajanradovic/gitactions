<?php

namespace App\Traits;

use App\Services\Auth\Paseto;
use App\Models\TokenBlacklist;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasJwt
{
	public function blacklistedTokens(): MorphMany
	{
		return $this->morphMany(TokenBlacklist::class, 'user');
	}

	public function getJwtId(): string
	{
		return $this->getKey();
	}

	public function getJwtValidFromTime(): ?Carbon
	{
		return null;
	}

	public function getJwtValidUntilTime(): ?Carbon
	{
		return setting('jwt_expiration_time') ? now()->addMinutes(setting('jwt_expiration_time')) : null;
	}

	public function getJwtCustomClaims(): array
	{
		return [];
	}

	public function token(array $config = []): string
	{
		$paseto = new Paseto;

		return $paseto->encodeToken($this, $config);
	}

	protected static function bootHasJwt(): void
	{
		static::deleting(function ($model): void {
			$model->blacklistedTokens->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
