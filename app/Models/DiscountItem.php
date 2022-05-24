<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountItem extends Model
{
	public function discount(): BelongsTo
	{
		return $this->belongsTo(Discount::class);
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class, 'item_id');
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'item_id');
	}

	public function item(): MorphTo
	{
		return $this->morphTo();
	}
}
