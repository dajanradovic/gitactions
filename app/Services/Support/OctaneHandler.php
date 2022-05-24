<?php

namespace App\Services\Support;

use App\Services\Config;
use Laravel\Octane\Events\RequestReceived;

class OctaneHandler
{
	public function handle(RequestReceived $event): void
	{
		$event->sandbox->instance('config', Config::bindWithSettings());
	}
}
