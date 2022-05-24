<?php

namespace App\Models;

use App\Traits\HasLimitedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Contracts\HasLimitedScope as HasLimitedScopeContract;

class Session extends Model implements HasLimitedScopeContract
{
	use Prunable, HasLimitedScope;

	protected $perPage = 15;

	protected $casts = [
		'last_activity' => 'datetime',
	];

	public function getIncrementing(): bool
	{
		return false;
	}

	public function getKeyType(): string
	{
		return 'string';
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function prunable(): Builder
	{
		if (!($days = setting('push_devices_cleanup_days'))) {
			return static::whereNull('id');
		}

		return static::where('last_activity', '<', formatTimestamp(now()->subDays($days), 'U'));
	}

	public function scopeUserScope(Builder $query): Builder
	{
		return $query->whereHas('user', function ($query): void {
			$query->userScope();
		});
	}
}
