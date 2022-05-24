<?php

namespace App\Traits;

use App\Models\DiscountItem;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDiscounts
{
	public function discounts(): MorphMany
	{
		return $this->morphMany(DiscountItem::class, 'item');
	}

	protected static function bootHasDiscounts(): void
	{
		static::deleting(function ($model): void {
			$model->discounts->each(function ($item): void {
				$item->delete();
			});
		});
	}
}
