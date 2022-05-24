<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Contracts\DiscountableItem;
use Illuminate\Database\Eloquent\Builder;

class ProductVariant extends Model implements DiscountableItem
{
	use HasTranslations;

	public function scopeIncludes(object $query, ?string $lang = null): Builder
	{
		return $query->translationIncludes($lang);
	}

	public function getCurrentlyApplicableDiscount(): Discount|null
	{
		return Discount::applicable()->whereHas('items', function ($query) {
			$query->where('item_id', $this->product_id);
		})->orderByDesc('amount')->orderByDesc('is_percentage')->first();
	}

	public function applyDiscount(?Discount $discount = null): ?string
	{
		if (!$discount) {
			return null;
		}

		return $discount->is_percentage ? bcsub($this->price, bcmul($this->price, bcdiv((string) $discount->amount, '100', 2), 2), 2) : bcsub($this->price, (string) $discount->amount, 2);
	}


	public function getVatRate(string $countryCode = 'HR'): int|float
	{
		return $this->product->category?->vatRates->firstWhere('country_code', $countryCode)->amount ?? setting('pdv_default');
	}

	public function getDiscountAmount(?Discount $discount = null): ?string
	{
		if (!$discount) {
			return null;
		}

		return $discount->is_percentage ? bcmul($this->price, bcdiv((string) $discount->amount, '100', 2), 2) : (string) $discount->amount;
	}
}
