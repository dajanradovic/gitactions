<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class VonageIncomingSms extends FormRequest
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
			'msisdn' => ['required', 'string', 'max:20'],
			'to' => ['required', 'string', 'max:20'],
			'messageId' => ['required', 'string', 'max:50'],
			'text' => ['required', 'string', 'max:1600']
		];
	}
}
