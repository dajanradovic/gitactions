<?php

namespace App\Models;

use App\Traits\HasLimitedScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Contracts\HasLimitedScope as HasLimitedScopeContract;

class UserActivity extends Model implements HasLimitedScopeContract
{
	use HasLimitedScope;

	public const TYPE_CREATE = 1;
	public const TYPE_READ = 2;
	public const TYPE_UPDATE = 3;
	public const TYPE_DELETE = 4;

	protected $casts = [
		'updated_fields' => 'array',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function item(): MorphTo
	{
		return $this->morphTo();
	}

	public function scopeIncludes(Builder $query): Builder
	{
		return $query->with([
			'item',
			'user' => function ($query): void {
				$query->includes();
			}
		]);
	}

	public function scopeSearch(Builder $query, ?string $search = null): Builder
	{
		return !$search ? $query : $query->whereHas('user', function ($query) use ($search): void {
			$query->search($search);
		});
	}

	public function scopeUserScope(Builder $query): Builder
	{
		return $query->whereHas('user', function ($query): void {
			$query->userScope();
		});
	}
}
