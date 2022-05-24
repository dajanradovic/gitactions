<?php

namespace App\Observers;

use App\Models\Webhook;
use App\Services\WebhookSchedulerHandler;

class WebhookObserver
{
	/**
	 * Handle the Webhook "created" event.
	 */
	public function created(Webhook $webhook): void
	{
	}

	/**
	 * Handle the Webhook "updated" event.
	 */
	public function updated(Webhook $webhook): void
	{
	}

	/**
	 * Handle the Webhook "deleted" event.
	 */
	public function deleted(Webhook $webhook): void
	{
		$webhookScheduler = new WebhookSchedulerHandler;
		$webhookScheduler->deleteWebhook($webhook->external_id);
	}

	/**
	 * Handle the Webhook "restored" event.
	 */
	public function restored(Webhook $webhook): void
	{
	}

	/**
	 * Handle the Webhook "force deleted" event.
	 */
	public function forceDeleted(Webhook $webhook): void
	{
	}
}
