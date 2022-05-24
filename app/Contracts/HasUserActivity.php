<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasUserActivity
{
	public function activities(): MorphMany;

	public function recordReadActivity(?bool $every = null, ?bool $update = null): self;

	public function recordEveryReadActivity(): bool;

	public function updateReadActivity(): bool;

	public function getUserActivityTitle(): ?string;

	public function getUserActivityUrl(): ?string;

	public function shouldRecordUserActivity(int $type): bool;
}
