<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotifications
{
	public function pushNotifications(): MorphMany
	{
		return $this->morphMany(Notification::class, 'parent');
	}

	public function scopeNotificationIncludes(Builder $query): Builder
	{
		return $query;
	}

	public function getNotificationId(): string
	{
		return $this->getKey();
	}

	public function getNotificationAvatar(): ?string
	{
		return null;
	}

	public function getNotificationName(): ?string
	{
		return null;
	}

	public function getNotificationType(): string
	{
		return strtolower(class_basename($this));
	}

	public function getAppDeeplinkUrl(?string $uri = null): string
	{
		$uri ??= $this->getNotificationId();

		return setting('app_scheme') . '/' . $uri;
	}

	protected static function bootHasNotifications(): void
	{
		static::deleting(function ($model): void {
			$model->pushNotifications->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
