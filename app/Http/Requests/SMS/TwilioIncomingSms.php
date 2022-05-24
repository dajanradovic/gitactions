<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class TwilioIncomingSms extends FormRequest
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
			'From' => ['required', 'string', 'max:20'],
			'To' => ['required', 'string', 'max:20'],
			'MessageSid' => ['required', 'string', 'max:50'],
			'Body' => ['required', 'string', 'max:1600']
		];
	}
}
