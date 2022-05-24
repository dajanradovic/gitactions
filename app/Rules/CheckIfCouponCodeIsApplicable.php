<?php

namespace App\Rules;

use App\Models\Discount;
use Illuminate\Contracts\Validation\Rule;

class CheckIfCouponCodeIsApplicable implements Rule
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
		$discount = Discount::where('code', $value)->first();

		return $discount && $discount->isAvailable();
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.coupon-code');
	}
}
