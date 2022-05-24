<?php

namespace App\Models;

use App\Traits\Countries;
use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use Illuminate\Support\Str;
use App\Traits\HasDiscounts;
use App\Traits\HasTranslations;
use Illuminate\Support\Collection;
use App\Observers\CategoryObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Contracts\HasTranslations as ContractsHasTranslations;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model implements HasMedia, ContractsHasTranslations
{
	use HasStorage, HasRecursiveRelationships, HasTranslations, Countries, HasDiscounts;

	protected $casts = [
		'active' => 'boolean',
		'use_parent_filters' => 'boolean',
		'adult_only' => 'boolean'
	];

	public function mediaConfig(): array
	{
		return [
			'image' => [
				'max' => 1
			],
		];
	}

	public function getParentKeyName(): string
	{
		return 'category_id';
	}

	public function categoryFilters(): HasMany
	{
		return $this->hasMany(CategoryFilter::class);
	}

	/*public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}*/

	public function parent(): BelongsTo
	{
		return $this->belongsTo(self::class, 'category_id');
	}

	public function children(): HasMany
	{
		return $this->hasMany(self::class, 'category_id');
	}

	public function vatRates(): HasMany
	{
		return $this->hasMany(VatRate::class);
	}

	public function scopeIncludes(object $query, ?string $lang = null): Builder
	{
		return $query->translationIncludes($lang)->with([
			'media',
			'categoryFilters' => function ($query) use ($lang) {
				$query->includes($lang)->available();
			}
		])
			->withCount([
				'children' => function ($query) {
					$query->available();
				}
			]);
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('active', true);
	}

	public function scopeBaseCategories(Builder $query): Builder
	{
		return $query->whereNull('category_id');
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

	public function scopeParent(Builder $query, bool $parent_exists, ?string $parent_category = null): Builder
	{
		if (!$parent_exists) {
			return $query;
		}

		return $query->where('category_id', $parent_category);
	}

	public function isRecursive(?string $category_id): bool
	{
		return $this->descendants()->where('id', $category_id)->exists();
	}

	public function setNameAttribute(?string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}

	public function storeFilters(?array $selected_filters = []): self
	{
		$selected_filters ??= [];

		$stored_filters = $this->categoryFilters->pluck('filter_id')->all();
		// delete all deselected
		$difference = array_diff($stored_filters, $selected_filters);
		$category_filters = $this->categoryFilters()->whereIn('filter_id', $difference)->get();

		foreach ($category_filters as $cf) {
			$cf->delete();
		}

		// create new if needed
		$selected_filters = array_diff($selected_filters, $stored_filters);

		foreach ($selected_filters as $filter) {
			$this->categoryFilters()->firstOrCreate([
				'filter_id' => $filter
			]);
		}

		return $this;
	}

	/**
	 * Returns only available Web filters (true parameter in includes).
	 */
	public function getFilters(?string $lang = null): Collection
	{
		return $this->use_parent_filters
			? CategoryFilter::select('*')->includes($lang)->available()->whereIn('category_id', $this->ancestorsAndSelf()->available()->get()->pluck('id')->all())->orderBy('category_id')->distinct('filter_id')->get()
			: $this->categoryFilters;
	}

	/**
	 * used only internally when adding product filters (all filters will be shown).
	 */
	public function getAllFilters(?string $lang = null): Collection
	{
		return $this->use_parent_filters
			? CategoryFilter::select('*')->includes($lang)->available()->whereIn('category_id', $this->ancestorsAndSelf()->available()->get()->pluck('id')->all())->orderBy('category_id')->distinct('filter_id')->get()
			: $this->categoryFilters;
	}

	public function setSlugAttribute(?string $value): void
	{
		$this->attributes['slug'] = Str::slug($value ?? $this->name);
	}

	protected static function initObservers(): ?string
	{
		return CategoryObserver::class;
	}
}
