<?php

namespace App\Observers;

use App\Models\IncomingSmsMessage;

class IncomingSmsMessageObserver
{
	/**
	 * Handle the IncomingSmsMessage "created" event.
	 */
	public function created(IncomingSmsMessage $incomingSmsMessage): void
	{
		$incomingSmsMessage->handleDefaultReply();
	}

	/**
	 * Handle the IncomingSmsMessage "updated" event.
	 */
	public function updated(IncomingSmsMessage $incomingSmsMessage): void
	{
	}

	/**
	 * Handle the IncomingSmsMessage "deleted" event.
	 */
	public function deleted(IncomingSmsMessage $incomingSmsMessage): void
	{
	}

	/**
	 * Handle the IncomingSmsMessage "restored" event.
	 */
	public function restored(IncomingSmsMessage $incomingSmsMessage): void
	{
	}

	/**
	 * Handle the IncomingSmsMessage "force deleted" event.
	 */
	public function forceDeleted(IncomingSmsMessage $incomingSmsMessage): void
	{
	}
}
