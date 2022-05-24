<?php

namespace App\Models;

use App\Observers\CategoryFilterObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryFilter extends Model
{
	public function scopeIncludes(Builder $query, ?string $lang = null): Builder
	{
		return $query->with([
			'filter' => function ($query) use ($lang) {
				$query->includes($lang);
			}
		]);
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->whereHas('filter', function ($query) {
			$query->available();
		});
	}

	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function filter(): BelongsTo
	{
		return $this->belongsTo(Filter::class);
	}

	protected static function initObservers(): ?string
	{
		return CategoryFilterObserver::class;
	}
}
