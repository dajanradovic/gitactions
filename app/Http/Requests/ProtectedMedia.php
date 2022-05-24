<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtectedMedia extends FormRequest
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
			'width' => ['nullable', 'integer', 'min:0'],
			'conversion' => ['nullable', 'string', 'max:50']
		];
	}
}
