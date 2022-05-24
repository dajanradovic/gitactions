<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class NthIncomingSms extends FormRequest
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
			'messageId' => ['required', 'uuid'],
			'phoneNumber' => ['required', 'string', 'max:20'],
			'receiver' => ['required', 'string', 'max:20'],
			'text' => ['required', 'string', 'max:1600']
		];
	}
}
