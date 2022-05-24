<?php

namespace App\Models;

use App\Observers\OrderShipmentObserver;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipment extends Model
{
	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}

	protected static function initObservers(): ?string
	{
		return OrderShipmentObserver::class;
	}
}
