<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Telescope;
use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		// Telescope::night();
		$this->hideSensitiveRequestDetails();

		Telescope::filter(function (IncomingEntry $entry) {
			return !($entry->type == EntryType::REQUEST && setting('telescope_same_ip')
				&& isset($entry->content['headers']['host'], $_SERVER['SERVER_ADDR'])
				&& stripos($entry->content['headers']['host'], $_SERVER['SERVER_ADDR']) !== false);

			/*return $entry->isReportableException() ||
				   $entry->isFailedRequest() ||
				   $entry->isFailedJob() ||
				   $entry->isScheduledTask() ||
				   $entry->hasMonitoredTag();*/
		});
	}

	/**
	 * Register the Telescope gate.
	 *
	 * This gate determines who can access Telescope in non-local environments.
	 */
	protected function gate(): void
	{
		Gate::define('viewTelescope', function (User $user): bool {
			return $user->isAvailable() && $user->canViewRoute('telescope-auth');
		});
	}

	/**
	 * Prevent sensitive request details from being logged by Telescope.
	 */
	protected function hideSensitiveRequestDetails(): void
	{
		Telescope::hideRequestParameters(['_token']);

		Telescope::hideRequestHeaders([
			'cookie',
			'x-csrf-token',
			'x-xsrf-token',
		]);
	}
}
