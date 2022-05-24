<?php

namespace App\Console;

use App\Models\Order;
use App\Jobs\GetOneSignalStats;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * Register the commands for the application.
	 */
	protected function commands(): void
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}

	/**
	 * Define the application's command schedule.
	 */
	protected function schedule(Schedule $schedule): void
	{
		if (!Schema::hasTable('settings')) {
			return;
		}

		if (setting('monitor_active')) {
			$schedule->command('monitor:check-uptime')->everyFiveMinutes();
			$schedule->command('monitor:check-certificate')->daily();
		}

		if ($pruneHours = setting('telescope_prune_hours')) {
			$schedule->command('queue:prune-batches --hours=' . $pruneHours)->daily();
			$schedule->command('queue:prune-failed --hours=' . $pruneHours)->daily();
			$schedule->command('telescope:prune --hours=' . $pruneHours)->daily();
		}

		$schedule->command('model:prune', [
			'--except' => [Order::class],
		])->daily();

		$schedule->command('model:prune', [
			'--model' => [Order::class],
		])->hourly();

		$schedule->job(new GetOneSignalStats)->hourly();

		$schedule->command('horizon:snapshot')->everyFiveMinutes();

		$schedule->command('backup:clean')->daily();

		if (setting('backup_keep_days')) {
			$schedule->command('backup:run --only-db')->daily();
		}
	}
}
