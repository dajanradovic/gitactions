<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class ElksIncomingSms extends FormRequest
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
			'id' => ['required', 'string', 'max:50'],
			'from' => ['required', 'string', 'max:20'],
			'to' => ['required', 'string', 'max:20'],
			'message' => ['required', 'string', 'max:1600'],
		];
	}
}
