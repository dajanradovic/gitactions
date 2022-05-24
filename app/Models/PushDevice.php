<?php

namespace App\Models;

use App\Traits\HasLimitedScope;
use App\Jobs\GetOneSignalDevice;
use App\Observers\PushDeviceObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Contracts\HasLimitedScope as HasLimitedScopeContract;

class PushDevice extends Model implements HasLimitedScopeContract
{
	use Prunable, HasLimitedScope;

	public const DEVICE_TYPE_IOS = 0;
	public const DEVICE_TYPE_ANDROID = 1;
	public const DEVICE_TYPE_AMAZON = 2;
	public const DEVICE_TYPE_WINDOWS_PHONE = 3;
	public const DEVICE_TYPE_CHROME_APP = 4;
	public const DEVICE_TYPE_CHROME_WEB_PUSH = 5;
	public const DEVICE_TYPE_WINDOWS = 6;
	public const DEVICE_TYPE_SAFARI = 7;
	public const DEVICE_TYPE_FIREFOX = 8;
	public const DEVICE_TYPE_MACOS = 9;
	public const DEVICE_TYPE_ALEXA = 10;
	public const DEVICE_TYPE_EMAIL = 11;
	public const DEVICE_TYPE_HUAWEI = 13;
	public const DEVICE_TYPE_SMS = 14;

	public function user(): MorphTo
	{
		return $this->morphTo();
	}

	public function getOneSignalDeviceType(): ?string
	{
		$device_types = [
			self::DEVICE_TYPE_IOS => 'iOS',
			self::DEVICE_TYPE_ANDROID => 'Android',
			self::DEVICE_TYPE_AMAZON => 'Amazon',
			self::DEVICE_TYPE_WINDOWS_PHONE => 'Windows Phone',
			self::DEVICE_TYPE_CHROME_APP => 'Chrome App / Extension',
			self::DEVICE_TYPE_CHROME_WEB_PUSH => 'Chrome Web Push',
			self::DEVICE_TYPE_WINDOWS => 'Windows',
			self::DEVICE_TYPE_SAFARI => 'Safari',
			self::DEVICE_TYPE_FIREFOX => 'Firefox',
			self::DEVICE_TYPE_MACOS => 'MacOS',
			self::DEVICE_TYPE_ALEXA => 'Alexa',
			self::DEVICE_TYPE_EMAIL => 'E-mail',
			self::DEVICE_TYPE_HUAWEI => 'Huawei',
			self::DEVICE_TYPE_SMS => 'SMS',
		];

		return $device_types[$this->device_type] ?? null;
	}

	public function getOneSignalDevice(bool $now = false): self
	{
		if (!setting('onesignal_app_id')) {
			return $this;
		}

		if ($now || app()->isLocal()) {
			GetOneSignalDevice::dispatchSync($this);
		} else {
			GetOneSignalDevice::dispatch($this);
		}

		return $this;
	}

	public function prunable(): Builder
	{
		if (!($days = setting('push_devices_cleanup_days'))) {
			return static::whereNull('id');
		}

		return static::where('updated_at', '<', formatTimestamp(now()->subDays($days)));
	}

	public function scopeUserScope(Builder $query): Builder
	{
		return $query->whereHas('user', function ($query): void {
			$query->userScope();
		});
	}

	protected static function initObservers(): ?string
	{
		return PushDeviceObserver::class;
	}
}
