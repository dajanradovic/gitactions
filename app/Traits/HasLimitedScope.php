<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasLimitedScope
{
	public function scopeUserScope(Builder $query): Builder
	{
		return $query;
	}

	protected function scopeLimitedScope(Builder $query): Builder
	{
		$user = auth()->user()->user ?? null;

		return $user && $user->hasLimitedScope($this) ? $this->scopeUserScope($query) : $query;
	}

	protected static function bootHasLimitedScope(): void
	{
		static::addGlobalScope('limited-scope', function ($query): void {
			$query->limitedScope();
		});
	}
}
