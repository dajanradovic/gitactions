<?php

namespace App\Jobs;

use App\Models\PushDevice;
use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use Illuminate\Queue\SerializesModels;
use App\Services\Push\OneSignalHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetOneSignalDevice implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	protected PushDevice $pushDevice;

	/**
	 * Create a new job instance.
	 */
	public function __construct(PushDevice $pushDevice)
	{
		$this->pushDevice = $pushDevice;
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
		$onesignal = $onesignal->getDevices($this->pushDevice->device_id);

		if (!isset($onesignal['identifier'])) {
			return;
		}

		$this->pushDevice->update([
			'app_version' => $onesignal['game_version'],
			'device_type' => $onesignal['device_type'],
			'device_os' => $onesignal['device_os'],
			'device_model' => $onesignal['device_model'],
			'timezone' => timezone_name_from_abbr('', $onesignal['timezone']) ?: null
		]);
	}
}
