<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $total_price_with_discounts
 * @property string $discounts
 */
class OrderItem extends Model
{
	protected $casts = [
		'discounts_applied' => 'array',
		'order_item_details' => 'array'
	];

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
