<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Spatie\UptimeMonitor\Models\Monitor;

class UptimeMonitor extends Monitor
{
	use UsesUuid;

	protected $table = 'monitors';

	protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
