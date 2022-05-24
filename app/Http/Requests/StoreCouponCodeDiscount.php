<?php

namespace App\Http\Requests;

use App\Models\Discount;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponCodeDiscount extends FormRequest
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
			'max_use' => ['required', 'integer', 'min:0'],
			'period_from' => ['nullable', 'date', 'required_with:period_to'],
			'period_to' => ['nullable', 'date', 'after:period_from'],
			'is_percentage' => ['boolean'],
			'amount' => ['required', 'numeric', 'min:0'],
			'active' => ['boolean'],
			'categories' => ['array', Rule::requiredIf($this->active && empty($this->products))],
			'categories.*' => ['nullable', 'uuid', 'exists:categories,id'],
			'products' => ['array',  Rule::requiredIf($this->active && empty($this->categories))],
			'products.*' => ['nullable', 'uuid', 'exists:products,id']
		];
	}
}
