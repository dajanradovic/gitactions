<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\Orders\Payments\PayPalPaymentService;

class VerifyPayPalPayment implements Rule
{
	protected string $amount;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct(string $amount)
	{
		$this->amount = $amount;
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
		$paypal = new PayPalPaymentService;

		return $paypal->isOrderValid($value, $this->amount);
	}

	/**
	 * Get the validation error message.
	 */
	public function message(): string|array
	{
		return __('validation.custom.invalid-paypal');
	}
}
