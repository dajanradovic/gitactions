<?php

namespace App\Jobs\Middleware;

use App\Services\Config;
use Illuminate\Contracts\Queue\ShouldQueue;

class BindConfigs
{
	/**
	 * Process the queued job.
	 */
	public function handle(ShouldQueue $job, callable $next): void
	{
		Config::bindWithSettings();

		$next($job);
	}
}
