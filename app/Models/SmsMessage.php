<?php

namespace App\Models;

use App\Jobs\SendSmsMessage;
use App\Observers\SmsMessageObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmsMessage extends Model
{
	public const DEFAULT_CURRENCY = 'USD';

	public const PROVIDER_VONAGE = 1;
	public const PROVIDER_TWILIO = 2;
	public const PROVIDER_INFOBIP = 3;
	public const PROVIDER_NTH = 4;
	public const PROVIDER_ELKS = 5;

	public function parent(): MorphTo
	{
		return $this->morphTo();
	}

	public static function getAvailableProviders(): array
	{
		return [
			self::PROVIDER_VONAGE,
			self::PROVIDER_TWILIO,
			self::PROVIDER_INFOBIP,
			self::PROVIDER_NTH,
			self::PROVIDER_ELKS,
		];
	}

	public function send(bool $now = false): self
	{
		if ($now || app()->isLocal()) {
			SendSmsMessage::dispatchSync($this);
		} else {
			SendSmsMessage::dispatch($this);
		}

		return $this;
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return !$search ? $query : $query->where(function ($query) use ($search): void {
			$query->orWhere('id', 'like', $search)
				->orWhere('external_id', 'like', $search)
				->orWhere('from', 'like', $search)
				->orWhere('to', 'like', $search);
		});
	}

	public function setFromAttribute(?string $value): void
	{
		$this->attributes['from'] = $value ? preg_replace('/\s+/', '', $value) : $this->getDefaultFrom();
	}

	public function setToAttribute(string $value): void
	{
		$this->attributes['to'] = preg_replace('/\s+/', '', $value);
	}

	public function setStatusAttribute(?string $value): void
	{
		$this->attributes['status'] = $value ? strtolower($value) : null;
	}

	public function setPriceCurrencyAttribute(?string $value): void
	{
		$this->attributes['price_currency'] = $value ?? self::DEFAULT_CURRENCY;
	}

	protected static function initObservers(): ?string
	{
		return SmsMessageObserver::class;
	}

	protected function getDefaultFrom(): ?string
	{
		switch ($this->provider) {
			case self::PROVIDER_VONAGE:
				return setting('vonage_from_number');

			case self::PROVIDER_TWILIO:
				return setting('twilio_from_number');

			case self::PROVIDER_INFOBIP:
				return setting('infobip_from_number');

			case self::PROVIDER_NTH:
				return setting('nth_from_number');

			case self::PROVIDER_ELKS:
				return setting('elks_from_number');

			default:
				return null;
		}
	}
}
