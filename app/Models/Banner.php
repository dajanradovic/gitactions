<?php

namespace App\Models;

use App\Traits\HasStorage;
use App\Contracts\HasMedia;
use Illuminate\Database\Eloquent\Builder;

class Banner extends Model implements HasMedia
{
	use HasStorage;

	public const BANNER_TYPE_HOME = 1;

	protected $casts = [
		'active' => 'boolean',
	];

	public static function getBannerTypeConstants(): array
	{
		return [
			self::BANNER_TYPE_HOME
		];
	}

	public function mediaConfig(): array
	{
		return [
			'image' => [
				'max' => 1
			],
			'image_mobile' => [
				'max' => 1
			],
		];
	}

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with('media');
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query->where('active', true);
	}

	public function scopeBannerType(Builder $query, ?int $banner_type): Builder
	{
		return !$banner_type ? $query : $query->where('type', $banner_type);
	}
}
