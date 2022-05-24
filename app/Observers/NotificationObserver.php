<?php

namespace App\Observers;

use App\Models\Notification;

class NotificationObserver
{
	/**
	 * Handle the notification "created" event.
	 */
	public function created(Notification $notification): void
	{
	}

	/**
	 * Handle the notification "deleted" event.
	 */
	public function deleting(Notification $notification): void
	{
		$notification->targets->each(function ($item): void {
			$item->delete();
		});

		$notification->cancel();
	}

	/**
	 * Handle the notification "force deleted" event.
	 */
	public function forceDeleted(Notification $notification): void
	{
	}

	/**
	 * Handle the notification "restored" event.
	 */
	public function restored(Notification $notification): void
	{
	}

	/**
	 * Handle the notification "updated" event.
	 */
	public function updated(Notification $notification): void
	{
	}
}
