<?php

namespace App\Traits;

use App\Models\Tag;
use App\Models\TagItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTags
{
	public function tags(): MorphMany
	{
		return $this->morphMany(TagItem::class, 'item');
	}

	public function syncTagsByName(string|array $tags = null): self
	{
		$this->tags()->delete();
		$tags ??= [];

		if (is_string($tags)) {
			$delimiter = $this->getTagDelimiter();
			$tags = explode(empty($delimiter) ? ' ' : $delimiter, $tags);
		}

		foreach ($tags as $tag) {
			$tag = Tag::firstOrCreate([
				'name' => $tag
			]);

			$this->tags()->create([
				'tag_id' => $tag->id
			]);
		}

		return $this;
	}

	public static function getTagDelimiter(): string
	{
		return ' ';
	}

	public function scopeSearchByTag(Builder $query, ?string $search = null): Builder
	{
		return !$search ? $query : $query->whereHas('tags', function ($query) use ($search): void {
			$query->whereHas('tag', function ($query) use ($search): void {
				$query->where('name', 'like', $search);
			});
		});
	}

	public function scopeSearchByTagIds(Builder $query, ?array $tags = []): Builder
	{
		return empty($tags) ? $query : $query->whereHas('tags', function ($query) use ($tags): void {
			$query->whereIn('tag_id', $tags);
		});
	}

	protected static function bootHasTags(): void
	{
		static::deleting(function ($model): void {
			$model->tags->each(function ($item): void {
				$item->delete();
			});
		});

		static::deleted(function ($model): void {
			$tags = Tag::doesntHave('items')->get();

			foreach ($tags as $tag) {
				$tag->delete();
			}
		});
	}
}
