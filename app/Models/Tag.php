<?php

namespace App\Models;

use App\Observers\TagObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
	use Prunable;

	public function items(): HasMany
	{
		return $this->hasMany(TagItem::class);
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return !$search ? $query : $query->where('name', 'like', $search);
	}

	public function scopeAvailable(Builder $query, string|array $types = '*'): Builder
	{
		return $query->whereHas('items', function ($query) use ($types): void {
			$query->whereHasMorph('item', $types, function ($query): void {
				$query->availableForTags();
			});
		});
	}

	public function setNameAttribute(string $value): void
	{
		$this->attributes['name'] = preg_replace('/\s+/', ' ', $value);
	}

	public function prunable(): Builder
	{
		return static::doesntHave('items');
	}

	protected static function initObservers(): ?string
	{
		return TagObserver::class;
	}
}
