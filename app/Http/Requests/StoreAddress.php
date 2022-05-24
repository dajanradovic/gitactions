<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddress extends FormRequest
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
			'name' => ['required', 'string', 'max:150'],
			'country_code' => ['required', 'string', Rule::in(Address::getCountries())],
			'street' => ['required', 'string', 'max:150'],
			'city' => ['required', 'string', 'max:50'],
			'type' => ['required', 'integer', Rule::in(Address::getTypes())],
			'zip_code' => ['required', 'string', 'max:20'],
			'phone' => ['nullable', 'digits_between:1,20']
		];
	}
}
