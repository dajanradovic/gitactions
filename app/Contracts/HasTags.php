<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasTags
{
	public function tags(): MorphMany;

	public function syncTagsByName(string|array $tags = null): self;

	public static function getTagDelimiter(): string;

	public function scopeSearchByTag(Builder $query, ?string $search = null): Builder;

	public function scopeSearchByTagIds(Builder $query, ?array $tags = []): Builder;
}
