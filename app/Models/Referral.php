<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
	protected $casts = [
		'resolved_at' => 'date',
	];

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class, 'referrer_id');
	}
}
