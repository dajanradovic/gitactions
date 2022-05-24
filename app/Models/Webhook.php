<?php

namespace App\Models;

use App\Observers\WebhookObserver;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Webhook extends Model
{
	public const REPETITIVE_OFF = 0;
	public const REPETITIVE_MINUTES = 1;
	public const REPETITIVE_HOURS = 2;
	public const REPETITIVE_DAYS = 3;
	public const REPETITIVE_WEEKS = 4;
	public const REPETITIVE_MONTHS = 5;
	public const REPETITIVE_QUARTERS = 6;
	public const REPETITIVE_YEARS = 7;

	public function item(): MorphTo
	{
		return $this->morphTo();
	}

	protected static function initObservers(): ?string
	{
		return WebhookObserver::class;
	}
}
