<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use Illuminate\Queue\SerializesModels;
use App\Services\Push\OneSignalHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetOneSignalStatsSingle implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	protected Notification $notification;

	/**
	 * Create a new job instance.
	 */
	public function __construct(Notification $notification)
	{
		$this->notification = $notification;
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
		$data = $onesignal->getNotifications($this->notification->external_id);

		if (!isset($data['id'])) {
			return;
		}

		$this->notification->update([
			'remaining' => $data['remaining'],
			'successful' => $data['successful'],
			'failed' => $data['failed'],
			'errored' => $data['errored'],
			'converted' => $data['converted'],
			'canceled' => $data['canceled'],
			'completed_at' => $data['completed_at'] ? formatTimestamp($data['completed_at']) : null,
		]);
	}
}
