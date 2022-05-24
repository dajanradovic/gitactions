<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryPrice extends FormRequest
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
			'up_to_2_kg' => ['required', 'integer', 'min:0'],
			'up_to_5_kg' => ['required', 'integer', 'min:0'],
			'up_to_10_kg' => ['required', 'integer', 'min:0'],
			'up_to_15_kg' => ['required', 'integer', 'min:0'],
			'up_to_20_kg' => ['required', 'integer', 'min:0'],
			'up_to_25_kg' => ['required', 'integer', 'min:0'],
			'up_to_32_kg' => ['required', 'integer', 'min:0'],
			'islands_extra' => ['nullable', 'integer', 'min:0'],
			'additional_costs' => ['required', 'integer', 'min:0']
		];
	}
}
