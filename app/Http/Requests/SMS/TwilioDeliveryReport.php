<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class TwilioDeliveryReport extends FormRequest
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
			'MessageSid' => ['required', 'string', 'max:50'],
			'From' => ['required', 'string', 'max:20'],
			'To' => ['required', 'string', 'max:20'],
			'MessageStatus' => ['required', 'string', 'max:20'],
		];
	}
}
