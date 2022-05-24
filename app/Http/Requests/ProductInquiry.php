<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductInquiry extends FormRequest
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
			'email' => ['required', 'email'],
			'message' => ['required', 'string', 'max:500'],
			'type' => ['required', 'integer', Rule::in([1, 2])]
		];
	}
}
