<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFilter extends Model
{
	use HasTranslations;

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with([
			'categoryFilter' => function ($query) {
				$query->includes();
			}
		]);
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->whereHas('categoryFilter', function ($query) {
			$query->available();
		});
	}

	public function categoryFilter(): BelongsTo
	{
		return $this->belongsTo(CategoryFilter::class, 'filter_categories_id');
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
