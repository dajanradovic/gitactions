<?php

namespace App\Models;

use App\Traits\HasTags;
use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use Illuminate\Support\Str;
use App\Traits\HasUserActivity;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\HasTags as HasTagsContract;
use App\Contracts\HasUserActivity as HasUserActivityContract;

class Blog extends Model implements HasMedia, HasTagsContract, HasUserActivityContract
{
	use HasStorage, HasTags, HasUserActivity;

	protected $casts = [
		'published_at' => 'datetime',
	];

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with(['tags', 'tags.tag', 'media']);
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('published_at', '<', formatTimestamp());
	}

	public function scopeAvailableForTags(Builder $query): Builder
	{
		return $this->scopeAvailable($query);
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return !$search ? $query : $query->where(function ($query) use ($search): void {
			$query->orWhere('id', 'like', $search)
				->orWhere('title', 'like', $search)
				->orWhere('slug', 'like', $search);
		});
	}

	public function getDefaultMediaPrefix(): string
	{
		return 'blogs';
	}

	public function mediaConfig(): array
	{
		return [
			'image' => [
				'max' => 1
			],
		];
	}

	public function getUserActivityTitle(): ?string
	{
		return $this->title;
	}

	public function getUserActivityUrl(): ?string
	{
		return route('blogs.edit', $this->id);
	}

	public function setPublishedAtAttribute(?string $value): void
	{
		$this->attributes['published_at'] = $value ?? formatTimestamp();
	}

	public function setTitleAttribute(string $value): void
	{
		$this->attributes['title'] = preg_replace('/\s+/', ' ', $value);
	}

	public function setSlugAttribute(?string $value): void
	{
		$this->attributes['slug'] = Str::slug($value ?? $this->title);
	}
}
