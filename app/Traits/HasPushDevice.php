<?php

namespace App\Traits;

use App\Models\PushDevice;
use App\Models\NotificationTarget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPushDevice
{
	public function devices(): MorphMany
	{
		return $this->morphMany(PushDevice::class, 'user');
	}

	public function isNotifiable(): bool
	{
		return $this->active && $this->allow_push_notifications && $this->devices()->exists();
	}

	public function scopeNotifiable(Builder $query): Builder
	{
		return $query->where([
			['allow_push_notifications', true],
			['active', true],
		])
		->has('devices');
	}

	public function notificationTargets(): MorphMany
	{
		return $this->morphMany(NotificationTarget::class, 'user');
	}

	public function markAllNotificationsAsSeen(): self
	{
		$this->notificationTargets()->whereNull('seen_at')->update(['seen_at' => formatTimestamp()]);

		return $this;
	}

	protected static function bootHasPushDevice(): void
	{
		static::deleting(function ($model): void {
			$model->devices->each(function ($item): void {
				$item->delete();
			});

			$model->notificationTargets->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
