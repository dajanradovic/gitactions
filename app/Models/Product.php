<?php

namespace App\Models;

use App\Traits\HasLikes;
use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use Illuminate\Support\Str;
use App\Traits\HasDiscounts;
use App\Traits\HasTranslations;
use App\Observers\ProductObserver;
use App\Contracts\DiscountableItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $price
 */
class Product extends Model implements HasMedia, DiscountableItem
{
	use HasTranslations, HasStorage, HasDiscounts, HasLikes;

	public const REGULAR_PRODUCT = 1;

	public const VINE_HARVEST_INQUIRY = 1;
	public const PRODUCT_AVAILABILITY_INQUIRY = 2;

	public const STATUS_AVAILABLE = 1;
	public const STATUS_NOT_CURRENTLY_AVAILABLE = 2;
	public const STATUS_UNAVAILABLE = 3;

	public const DYNAMIC_PIKTOGRAM_NOVO = 14; // automatically applied if product is new (> now()->subMonth())
	public const DYNAMIC_PIKTOGRAM_DISCOUNT = 15; // automatically applied if product currently has discount applied)

	public const PIKTOGRAMS = [
		1 => 'OVCA',
		2 => 'KRAVA',
		3 => 'KOZA',
		4 => 'KRAVA/OVCA',
		5 => 'KRAVA/KOZA',
		6 => 'KRAVA/OVCA/KOZA',
		7 => 'OVCA/KOZA',
		8 => 'GMO-FREE',
		9 => 'BIO',
		10 => 'LACOSTE-FREE',
		11 => 'VEGAN',
		12 => 'VEGETARIAN',
		13 => 'BESPLATNA DOSTAVA',
	];

	public const MEASURE_UNITS = [
		1 => 'KG',
		2 => 'KOM'
	];

	public const STOCK_STORE_CODE = '011';

	protected $casts = [
		'active' => 'boolean',
		'gratis' => 'boolean',
		'piktograms' => 'array',
		'unavailable' => 'boolean',
		'unit_of_measure' => 'integer',
		'type' => 'integer',
		'sort_number' => 'integer'
	];

	public function mediaConfig(): array
	{
		return [
			'images' => [
				'max' => 10
			],
		];
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function productFilters(): HasMany
	{
		return $this->hasMany(ProductFilter::class);
	}

	public function scopeIncludes(object $query, ?string $lang = null): Builder
	{
		return $query->translationIncludes($lang)->with([
			'media',
			'category' => function ($query) use ($lang) {
				$query->includes($lang)->available();
			},
			'category.vatRates',
			'variants' => function ($query) use ($lang) {
				$query->includes($lang)->available();
			},
			'productFilters' => function ($query) {
				$query->includes()->available();
			},
		])->withAvg('reviews', 'rating');
	}

	public function saveFilterValues(?array $filters = []): self
	{
		$this->productFilters()->delete();

		$filters ??= [];
		$category = $this->category;

		$categoryFilters = $category->use_parent_filters
			? CategoryFilter::whereIn('category_id', $category->ancestorsAndSelf()->available()->get()->pluck('id')->all())->whereIn('filter_id', array_keys($filters))->get()->unique('filter_id')
			: $category->categoryFilters()->available()->whereIn('filter_id', array_keys($filters))->get();

		foreach ($filters as $filter_id => $value) {
			$filter_id = $categoryFilters->where('filter_id', $filter_id)->first()->id ?? null;

			if (!$filter_id || $value == null) {
				continue;
			}

			$this->productFilters()->create([
				'filter_categories_id' => $filter_id,
				'value' => $value
			]);
		}

		return $this;
	}

	public function scopeSearch(Builder $query, ?string $search = null, ?string $lang = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return !$search ? $query : $query->where(function ($query) use ($search) {
			$query->where('name', 'like', $search)
				->orWhere('slug', 'like', $search);
		})->when($lang && ($lang !== static::defaultTranslationLocale()), function ($query) use ($search) {
			$query->orWhere(function ($query) use ($search) {
				$query->whereHas('translations', function ($query) use ($search) {
					$query->where('column', 'name')->where(function ($query) use ($search) {
						$query->orWhere('value', 'like', $search);
					})->orWhere('column', 'slug')->where(function ($query) use ($search) {
						$query->orWhere('value', 'like', $search);
					});
				});
			});
		});
	}

	public function setNameAttribute(?string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}

	public static function generateSlug(string $productTitle): string
	{
		$original_slug = strtolower(Str::slug($productTitle));

		if (self::where('slug', $original_slug)->exists()) {
			// Add UUID chunk to the end, untill it does not exist
			// if this chunk exists with this ID, try another chunk (won't happen but could happen)
			do {
				$uuid_chunk = explode('-', Str::uuid())[0];
				$slug = $original_slug . '-' . $uuid_chunk;
			} while (self::where('slug', $slug)->exists());
			$original_slug = $slug;
		}

		return $original_slug;
	}

	public function scopeUpsell(Builder $query, Product $product): Builder
	{
		return $query->where('id', '<>', $product->id)->where('category_id', $product->category_id)->where('price', '>', $product->price);
	}

	public function scopeAdultOnly(Builder $query, ?bool $adultOnly = null): Builder
	{
		return !$adultOnly ? $query : $query->whereHas('category', function ($query) use ($adultOnly) {
			$query->where('adultOnly', $adultOnly);
		});
	}

	public function fetchPiktograms(?Discount $on_discount = null): array|null
	{
		$collection = collect($this->piktograms);

		if ($on_discount) {
			$collection = $collection->merge([(string) self::DYNAMIC_PIKTOGRAM_DISCOUNT]);
		}

		if ($this->created_at > now()->subMonth()) {
			$collection = $collection->merge([(string) self::DYNAMIC_PIKTOGRAM_NOVO]);
		}

		return $collection->all();
	}

	public function variants(): HasMany
	{
		return $this->hasMany(ProductVariant::class);
	}

	public static function getMeasureUnites(): array
	{
		return self::MEASURE_UNITS;
	}

	public function availabilityStatus(): int
	{
		return $this->unavailable ? self::STATUS_UNAVAILABLE : ($this->quantity > 0 ? self::STATUS_AVAILABLE : self::STATUS_NOT_CURRENTLY_AVAILABLE);
	}

	public function getCurrentlyApplicableDiscount(): Discount|null
	{
		return Discount::applicable()->whereHas('items', function ($query) {
			$query->where('item_id', $this->id);
		})->orderByDesc('amount')->orderByDesc('is_percentage')->first();
	}

	public function applyDiscount(?Discount $discount = null): ?string
	{
		if (!$discount) {
			return null;
		}

		return $discount->is_percentage ? bcsub($this->price, bcmul($this->price, bcdiv((string) $discount->amount, '100', 2), 2), 2) : bcsub($this->price, (string) $discount->amount, 2);
	}

	public function reviews(): HasMany
	{
		return $this->hasMany(Review::class);
	}

	public static function getSecondPrice(?string $startingPrice = null): string|null
	{
		if (!$startingPrice) {
			return null;
		}

		return setting('main_currency') == Setting::CURRENCY_KUNA ? bcdiv($startingPrice, setting('currency_exchange_rate') ?? '7.5', 2) : bcmul($startingPrice, setting('currency_exchange_rate') ?? '7.5', 2);
	}

	public function getVatRate(string $countryCode = 'HR'): int|float
	{
		return $this->category?->vatRates->firstWhere('country_code', $countryCode)->amount ?? setting('pdv_default');
	}

	public function getDiscountAmount(?Discount $discount = null): ?string
	{
		if (!$discount) {
			return null;
		}

		return $discount->is_percentage ? bcmul($this->price, bcdiv((string) $discount->amount, '100', 2), 2) : (string) $discount->amount;
	}

	protected static function initObservers(): ?string
	{
		return ProductObserver::class;
	}

	public function scopeDynamicFilters(Builder $query, ?array $filters = []): Builder
	{
		$filters ??= [];

		foreach ($filters as $key => $value) {
			if (!is_array($value)) {
				$value = ['=' => $value];
			}

			$query = $query->dynamicFilter($key, $value);
		}

		return $query;
	}

	public function scopeDynamicFilter(Builder $query, string $filter_id, array $values): Builder
	{
		return $query->whereHas('productFilters', function ($query) use ($values, $filter_id) {
			$whereIn = [];

			foreach ($values as $operand => $value) {
				if ($value == null) {
					continue;
				}

				if (is_int($operand)) {
					$whereIn[] = $value;

					continue;
				}

				if ($operand == 'like') {
					$value = '%' . preg_replace('/\s+/', '%', $value) . '%';
				}

				// if($filter = Filter::find($filter_id)->translations()->where('column', 'value')->exists()){
				// 	$filter = Filter::find($filter_id);
				//  $valuesss = $filter->getTestFilterValue('en', 'value'));

				// }

				$query = $query->where('value', $operand, $value)
					->orWhereHas('categoryFilter.filter.translations', function ($query) use ($operand, $value) {
						$query->whereIn('value', $operand, $value);
					});
			}

			$query->when(!empty($whereIn), function ($query) use ($whereIn) {
				$query->whereIn('value', $whereIn)
					->orWhereHas('translations', function ($query) use ($whereIn) {
						$query->whereIn('value', $whereIn);
					});
			})
			->whereHas('categoryFilter', function ($query) use ($filter_id) {
				$query->whereHas('filter', function ($query) use ($filter_id) {
					$query->searchable()->where('id', $filter_id);
				});
			});
		});
	}

	// public function saveFilterTranslations(string $lang, ?array $filters = []): self
	// {
	// 	$filters ??= [];

	// 	foreach ($filters as $filter_id => $value) {
	// 		$productFilter = $this->productFilters()->whereHas('categoryFilter', function ($query) use ($filter_id) {
	// 			$query->where('filter_id', $filter_id);
	// 		})->first();

	// 		if ($productFilter) {
	// 			$productFilter->updateTranslations([
	// 				'value' => [
	// 					'en' => $request->name_en
	// 				]

	// 			]);
	// 		}
	// 	}

	// 	return $this;
	// }
}
