<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckIfAboveSetPriceLimitDiscountCanBeApplied;

class StoreAboveSetPriceDiscount extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:100'],
			'period_from' => ['nullable', 'date', 'required_with:period_to'],
			'period_to' => ['nullable', 'date', 'after:period_from'],
			'is_percentage' => ['boolean'],
			'amount' => ['required', 'numeric', 'min:0'],
			'active' => ['boolean', new CheckIfAboveSetPriceLimitDiscountCanBeApplied],
		];
	}
}
