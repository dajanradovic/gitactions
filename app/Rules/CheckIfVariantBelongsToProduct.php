<?php

namespace App\Rules;

use App\Models\ProductVariant;
use Illuminate\Contracts\Validation\Rule;

class CheckIfVariantBelongsToProduct implements Rule
{
	private string $productId;

	/**
	 * Create a new rule instance.
	 */
	public function __construct(?string $id = null)
	{
		$this->productId = $id;
	}

	public function __toString(): string
	{
		return self::class;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 */
	public function passes($attribute, $value): bool
	{
		return ProductVariant::where('id', $value)->where('product_id', $this->productId)->exists();
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.product-variant');
	}
}
