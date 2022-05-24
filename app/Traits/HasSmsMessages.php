<?php

namespace App\Traits;

use App\Models\SmsMessage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSmsMessages
{
	public function smsMessages(): MorphMany
	{
		return $this->morphMany(SmsMessage::class, 'parent');
	}

	public function getSmsFromNumber(): ?string
	{
		return null;
	}

	public function sendSmsWithVonage(string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->sendSmsWithProvider(SmsMessage::PROVIDER_VONAGE, $to, $body, $from);
	}

	public function sendSmsWithTwilio(string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->sendSmsWithProvider(SmsMessage::PROVIDER_TWILIO, $to, $body, $from);
	}

	public function sendSmsWithInfoBip(string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->sendSmsWithProvider(SmsMessage::PROVIDER_INFOBIP, $to, $body, $from);
	}

	public function sendSmsWithNth(string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->sendSmsWithProvider(SmsMessage::PROVIDER_NTH, $to, $body, $from);
	}

	public function sendSmsWithElks(string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->sendSmsWithProvider(SmsMessage::PROVIDER_ELKS, $to, $body, $from);
	}

	protected static function bootHasSmsMessages(): void
	{
		static::deleting(function ($model): void {
			$model->smsMessages->each(function ($item): void {
				$item->delete();
			});
		});
	}

	protected function sendSmsWithProvider(int $provider, string $to, string $body, ?string $from = null): SmsMessage
	{
		return $this->smsMessages()->create([
			'provider' => $provider,
			'to' => $to,
			'body' => $body,
			'from' => $from ?? $this->getSmsFromNumber()
		]);
	}
}
