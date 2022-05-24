<?php

namespace App\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLikes
{
	public function likes(): MorphMany
	{
		return $this->morphMany(Like::class, 'item');
	}

	public function scopeLiked(Builder $query): Builder
	{
		return $query->whereHas('likes', function ($query) {
			$query->where('customer_id', auth()->user()->user->id);
		});
	}

	public function hasUserLiked(): bool
	{
		$user = auth()->user()->user ?? null;

		return $user ? $this->likes()->where('customer_id', $user->id)->exists() : false;
	}

	protected static function bootHasLikes(): void
	{
		static::deleting(function ($model) {
			$model->likes->each(function ($item) {
				$item->delete();
			});
		});
	}
}
