<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
	public const TYPE_PRODUCT = 'PRODUCT';
	public const TYPE_GENERAL = 'GENERAL';
	protected $casts = [
		'active' => 'boolean',
		'rating' => 'integer'
	];

	public function author(): BelongsTo
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	public function scopeGeneralReview(Builder $query): Builder
	{
		return $query->whereNull('product_id')->whereNull('rating');
	}

	public function scopeProductReview(Builder $query): Builder
	{
		return $query->whereNotNull('product_id')->whereNotNull('rating')->whereNull('description');
	}

	public function scopeIncludes(object $query): Builder
	{
		return $query->includes()->with([
			'author' => function ($query) {
				$query->includes()->available();
			}
		]);
	}

	public function determineType(): string
	{
		return $this->product()->exists() ? self::TYPE_PRODUCT : self::TYPE_GENERAL;
	}
}
