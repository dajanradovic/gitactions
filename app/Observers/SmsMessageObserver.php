<?php

namespace App\Observers;

use App\Models\SmsMessage;

class SmsMessageObserver
{
	/**
	 * Handle the SmsMessage "created" event.
	 */
	public function created(SmsMessage $smsMessage): void
	{
		$smsMessage->send();
	}

	/**
	 * Handle the SmsMessage "updated" event.
	 */
	public function updated(SmsMessage $smsMessage): void
	{
	}

	/**
	 * Handle the SmsMessage "deleted" event.
	 */
	public function deleted(SmsMessage $smsMessage): void
	{
	}

	/**
	 * Handle the SmsMessage "restored" event.
	 */
	public function restored(SmsMessage $smsMessage): void
	{
	}

	/**
	 * Handle the SmsMessage "force deleted" event.
	 */
	public function forceDeleted(SmsMessage $smsMessage): void
	{
	}
}
