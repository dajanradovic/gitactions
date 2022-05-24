<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasNotifications
{
	public function pushNotifications(): MorphMany;

	public function scopeNotificationIncludes(Builder $query): Builder;

	public function getNotificationId(): string;

	public function getNotificationAvatar(): ?string;

	public function getNotificationName(): ?string;

	public function getNotificationType(): string;

	public function getAppDeeplinkUrl(?string $uri = null): string;
}
