<?php

namespace App\Contracts;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasJwt
{
	public function blacklistedTokens(): MorphMany;

	public function getJwtId(): string;

	public function getJwtValidFromTime(): ?Carbon;

	public function getJwtValidUntilTime(): ?Carbon;

	public function getJwtCustomClaims(): array;

	public function token(array $config = []): string;
}
