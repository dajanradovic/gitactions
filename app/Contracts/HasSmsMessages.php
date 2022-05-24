<?php

namespace App\Contracts;

use App\Models\SmsMessage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasSmsMessages
{
	public function smsMessages(): MorphMany;

	public function getSmsFromNumber(): ?string;

	public function sendSmsWithVonage(string $to, string $body, ?string $from = null): SmsMessage;

	public function sendSmsWithTwilio(string $to, string $body, ?string $from = null): SmsMessage;

	public function sendSmsWithInfoBip(string $to, string $body, ?string $from = null): SmsMessage;

	public function sendSmsWithNth(string $to, string $body, ?string $from = null): SmsMessage;

	public function sendSmsWithElks(string $to, string $body, ?string $from = null): SmsMessage;
}
