<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfAboveSetPriceLimitDiscountCanBeApplied implements Rule
{
	/**
	 * Create a new rule instance.
	 */
	public function __construct()
	{

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
		if ($value) {
			return setting('order_final_amount_discount_limit') > 0;
		}

		return true;
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.order_final_amount_discount_limit');
	}
}
