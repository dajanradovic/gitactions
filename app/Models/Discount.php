<?php

namespace App\Models;

use App\Observers\DiscountObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
	public const GENERAL_DISCOUNT = 3;
	public const ABOVE_SET_PRICE_DISCOUNT = 4;

	protected $casts = [
		'period-from' => 'date',
		'period-to' => 'date',
		'active' => 'boolean',
		'type' => 'integer',
		'is_percentage' => 'boolean',
		'add_up_with_other_discounts' => 'boolean'
	];

	public function items(): HasMany
	{
		return $this->hasMany(DiscountItem::class);
	}

	public static function getDiscountTypes(): array
	{
		return [
			self::GENERAL_DISCOUNT,
			self::ABOVE_SET_PRICE_DISCOUNT
		];
	}

	public function getDiscountItemsIds(string $selfClass): array
	{
		return $this->items()->where('item_type', $selfClass)->pluck('item_id')->toArray();
	}

	public function storeDiscountItems(): void
	{
		$this->items->each(function ($item) {
			$item->delete();
		});

		if (is_array(request()->categories)) {
			foreach (request()->categories as $category) {
				$this->items()->create([
					'item_type' => Category::class,
					'item_id' => $category
				]);
			}
		}

		if (is_array(request()->products)) {
			foreach (request()->products as $product) {
				$this->items()->create([
					'item_type' => Product::class,
					'item_id' => $product
				]);
			}
		}
	}

	public function isAvailable(): bool
	{
		// Check if discount is active and still usable
		if (!$this->active || ($this->max_use > 0 /* && $this->orders()->count() >= $this->max_use */)) {
			return false;
		}

		return $this->isActiveDatesWise();
	}

	public function isActiveDatesWise(): bool
	{
		if (!$this->period_from && !$this->period_to) {
			return true;
		}

		if ($this->period_from && (now() > $this->period_from) && !$this->period_to) {
			return true;
		}

		return (bool) ($this->period_from && (now() > $this->period_from) && (now() < $this->period_to));
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('active', true);
	}

	public function scopeApplicable(object $query): Builder
	{
		return $query->available()->whereNull('code')
		->where(function ($query) {
			$query->where(function ($query) {
				$query->whereNull('period_from')->whereNull('period_to');
			})->orWhere(function ($query) {
				$query->whereNull('period_to')->where('period_from', '<', now());
			})->orWhere(function ($query) {
				$query->where('period_from', '<', now())->where('period_to', '>', now());
			});
		});
	}

	public function doesAddUp(): bool
	{
		return $this->add_up_with_other_discounts;
	}

	public function isPercentage(): bool
	{
		return $this->is_percentage;
	}

	public function getType(): string
	{
		return $this->isPercentage() ? '%' : 'value';
	}

	public function getEditViewPath(): string
	{
		if ($this->code) {
			return 'discounts.edit-coupons';
		}

		if ($this->type == self::ABOVE_SET_PRICE_DISCOUNT) {
			return 'discounts.edit-above-set-price-discount';
		}

		return 'discounts.edit';
	}

	protected static function initObservers(): ?string
	{
		return DiscountObserver::class;
	}
}
