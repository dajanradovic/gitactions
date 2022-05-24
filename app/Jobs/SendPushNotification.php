<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use App\Services\Push\ExpoPushHandler;
use Illuminate\Queue\SerializesModels;
use App\Services\Push\OneSignalHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPushNotification implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public const PUSH_HANDLER = OneSignalHandler::class;

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
		switch (self::PUSH_HANDLER) {
			case OneSignalHandler::class:
				$this->sendOneSignal();

				return;

			case ExpoPushHandler::class:
				$this->sendExpo();

				return;

		}
	}

	protected function sendExpo(): ?array
	{
		if ($this->notification->scheduled_at > now()) {
			return null;
		}

		$data = [
			'title' => $this->notification->title,
			'body' => $this->notification->body,
			'sound' => 'default',
			'_displayInForeground' => true,
			'to' => $this->notification->getTargets(),
			'data' => json_encode($this->notification->resourceArray()),
		];

		$expo = new ExpoPushHandler;
		$expo = $expo->send($data);

		if (!isset($expo['data'][0]['id'])) {
			return null;
		}

		$this->notification->update([
			'external_id' => $expo['data'][0]['id'],
			'completed_at' => formatTimestamp(),
		]);

		return $expo;
	}

	protected function sendOneSignal(): ?array
	{
		if (!setting('onesignal_app_id')) {
			return null;
		}

		$filters = [];

		$data = [
			'headings' => [
				'en' => $this->notification->title,
			],
			'contents' => [
				'en' => $this->notification->body,
			],
			'data' => $this->notification->resourceArray(),
		];

		if ($this->notification->shouldIncludeUrlParam()) {
			$data['url'] = $this->notification->url;
		}

		if ($this->notification->scheduled_at > now()) {
			$data['send_after'] = $this->notification->scheduled_at;
		}

		if ($this->notification->collapse_id) {
			$data['collapse_id'] = $this->notification->collapse_id;
		}

		foreach (($this->notification->countries ?? []) as $country) {
			$filters[] = ['field' => 'country', 'relation' => '=', 'value' => $country];
			$filters[] = ['operator' => 'OR'];
		}

		if ($this->notification->radius) {
			$filters[] = ['field' => 'location', 'radius' => $this->notification->radius, 'lat' => $this->notification->location_lat, 'long' => $this->notification->location_lng];
		}

		if (empty($filters)) {
			$data['include_player_ids'] = $this->notification->getTargets();
		} else {
			$data['filters'] = $filters;
		}

		$onesignal = new OneSignalHandler;
		$onesignal = $onesignal->addNotification($data);

		if (!isset($onesignal['id'])) {
			return null;
		}

		$this->notification->update([
			'external_id' => $onesignal['id'],
		]);

		return $onesignal;
	}
}
