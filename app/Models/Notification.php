<?php

namespace App\Models;

use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use App\Jobs\SendPushNotification;
use App\Jobs\CancelPushNotification;
use App\Jobs\GetOneSignalStatsSingle;
use App\Observers\NotificationObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model implements HasMedia
{
	use Prunable, HasStorage;

	protected $casts = [
		'canceled' => 'boolean',
		'active' => 'boolean',
		'countries' => 'array',
		'scheduled_at' => 'datetime',
		'completed_at' => 'datetime',
	];

	public function getTargets(): array
	{
		return $this->targets()
			->with('user.devices')
			->get()
			->map(function (NotificationTarget $target): array {
				return $target->user->devices->pluck('device_id')->all();
			})
			->collapse()
			->unique()
			->values()
			->all();
	}

	public function getUserSeenAt(): ?string
	{
		return $this->userTarget && $this->userTarget->seen_at ? formatTimestamp($this->userTarget->seen_at) : null;
	}

	public function markAsSeen(): self
	{
		$this->userTarget()->whereNull('seen_at')->update(['seen_at' => formatTimestamp()]);

		return $this;
	}

	public function isAvailable(): bool
	{
		return $this->active && !$this->canceled && $this->scheduled_at < now() && $this->userTarget()->exists();
	}

	public function isCancelable(): bool
	{
		return !$this->canceled && $this->external_id && $this->scheduled_at > now();
	}

	public function resourceArray(): array
	{
		return [
			'id' => $this->id,
			'parent' => !$this->parent ? null : [
				'id' => $this->parent->getNotificationId(),
				'name' => $this->parent->getNotificationName(),
				'type' => $this->parent->getNotificationType(),
				'avatar' => $this->parent->getNotificationAvatar(),
			],
			'title' => $this->title,
			'body' => $this->body,
			'url' => $this->url,
			'seen_at' => $this->getUserSeenAt(),
			'completed_at' => $this->completed_at ? formatTimestamp($this->completed_at) : null,
			'scheduled_at' => formatTimestamp($this->scheduled_at),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}

	public function shouldIncludeUrlParam(?string $url = null): bool
	{
		$url ??= $this->url;

		return $url && str_starts_with($url, 'http');
	}

	public function getDefaultMediaPrefix(): string
	{
		return 'notifications';
	}

	public function mediaConfig(): array
	{
		return [
			'file' => [
				'max' => 1
			],
		];
	}

	public function parent(): MorphTo
	{
		return $this->morphTo();
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where([
			['active', true],
			['canceled', false],
			['scheduled_at', '<', formatTimestamp()],
		])
		->has('userTarget');
	}

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with([
			'parent' => function ($query): void {
				$query->notificationIncludes();
			},
			'userTarget'
		]);
	}

	public function send(bool $now = false): self
	{
		if (!setting('onesignal_app_id')) {
			return $this;
		}

		if ($now || app()->isLocal()) {
			SendPushNotification::dispatchSync($this);
		} else {
			SendPushNotification::dispatch($this);
		}

		return $this;
	}

	public function cancel(bool $now = false): self
	{
		if (!$this->isCancelable() || !setting('onesignal_app_id')) {
			return $this;
		}

		if ($now || app()->isLocal()) {
			CancelPushNotification::dispatchSync($this->external_id);
		} else {
			CancelPushNotification::dispatch($this->external_id);
		}

		$this->update([
			'canceled' => true
		]);

		return $this;
	}

	public function scopeReadyForStatsCheck(Builder $query): Builder
	{
		return $query->where('canceled', false)
			->whereNotNull('external_id')
			->whereBetween('scheduled_at', [formatTimestamp(now()->subDays(setting('onesignal_stats_check_days'))), formatTimestamp()])
			->where(function ($query): void {
				$query->whereNull('completed_at')->orWhereColumn('converted', '<', 'successful');
			});
	}

	public function checkStatistics(bool $now = false): self
	{
		if (!setting('onesignal_app_id')) {
			return $this;
		}

		if ($now || app()->isLocal()) {
			GetOneSignalStatsSingle::dispatchSync($this);
		} else {
			GetOneSignalStatsSingle::dispatch($this);
		}

		return $this;
	}

	public function setScheduledAtAttribute(?string $value): void
	{
		$this->attributes['scheduled_at'] = $value ?? formatTimestamp();
	}

	public function setTitleAttribute(string $value): void
	{
		$this->attributes['title'] = preg_replace('/\s+/', ' ', $value);
	}

	public function targets(): HasMany
	{
		return $this->hasMany(NotificationTarget::class);
	}

	public function userTarget(): HasOne
	{
		return $this->hasOne(NotificationTarget::class)->where('user_id', auth()->user()->id ?? null);
	}

	public function prunable(): Builder
	{
		return static::doesntHave('targets');
	}

	protected static function initObservers(): ?string
	{
		return NotificationObserver::class;
	}
}
