<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestCalculateTotal extends FormRequest
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
			'items' => ['required', 'array'],
			'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
			'items.*.variant_id' => ['nullable', 'uuid', 'exists:product_variants,id', 'bail'],
			'items.*.quantity' => ['required', 'integer', 'min:0'],
		];
	}
}
