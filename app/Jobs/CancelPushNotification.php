<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use Illuminate\Queue\SerializesModels;
use App\Services\Push\OneSignalHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CancelPushNotification implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected string $notificationId;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(string $notificationId)
	{
		$this->notificationId = $notificationId;
	}

	public function middleware(): array
	{
		return [new BindConfigs];
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		if (!setting('onesignal_app_id')) {
			return;
		}

		$onesignal = new OneSignalHandler;
		$onesignal->cancelNotification($this->notificationId);
	}
}
