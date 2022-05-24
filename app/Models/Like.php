<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
	public function item(): BelongsTo
	{
		return $this->morphTo();
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}
}
