<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
	public function getIncrementing(): bool
	{
		return false;
	}

	public function getKeyType(): string
	{
		return 'string';
	}

	protected static function bootUsesUuid(): void
	{
		static::creating(function ($model): void {
			$model->{$model->getKeyName()} = (string) Str::orderedUuid();
		});
	}
}
