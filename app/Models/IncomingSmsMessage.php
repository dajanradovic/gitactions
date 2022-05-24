<?php

namespace App\Models;

use App\Observers\IncomingSmsMessageObserver;

class IncomingSmsMessage extends Model
{
	public function handleDefaultReply(): ?SmsMessage
	{
		if (!($body = setting('sms_default_reply'))) {
			return null;
		}

		return SmsMessage::create([
			'provider' => $this->provider,
			'from' => $this->to,
			'to' => $this->from,
			'body' => $body,
		]);
	}

	public function setFromAttribute(string $value): void
	{
		$this->attributes['from'] = preg_replace('/\s+/', '', $value);
	}

	public function setToAttribute(string $value): void
	{
		$this->attributes['to'] = preg_replace('/\s+/', '', $value);
	}

	protected static function initObservers(): ?string
	{
		return IncomingSmsMessageObserver::class;
	}
}
