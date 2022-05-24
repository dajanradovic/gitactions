<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class SearchCountries extends FormRequest
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
			'fields' => ['array'],
			'fields.*' => ['required', 'string', 'max:50'],
		];
	}
}
