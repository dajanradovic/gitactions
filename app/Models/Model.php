<?php

namespace App\Models;

use DateTimeInterface;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class Model extends Eloquent
{
	use UsesUuid, HasFactory;

	protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

	protected $perPage = 15;

	public function scopeIncludes(Builder $query): Builder
	{
		return $query;
	}

	public function scopeAvailable(Builder $query): Builder
	{
		return $query;
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		$search = preg_replace('/\s+/', '%', $search ?? '');
		$search = empty($search) ? null : '%' . $search . '%';

		return $search ? $query->where($this->getKeyName(), 'like', $search) : $query;
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	protected function serializeDate(DateTimeInterface $date): string
	{
		return $date->format('Y-m-d H:i:s');
	}

	protected static function initObservers(): ?string
	{
		return null;
	}

	protected static function boot(): void
	{
		parent::boot();

		static::observe(static::initObservers() ?? []);
	}
}
