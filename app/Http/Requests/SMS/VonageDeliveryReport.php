<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class VonageDeliveryReport extends FormRequest
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
			'messageId' => ['required', 'string', 'max:50'],
			'msisdn' => ['required', 'string', 'max:20'],
			'to' => ['required', 'string', 'max:20'],
			'status' => ['required', 'string', 'max:20'],
			'err-code' => ['required', 'integer', 'min:0']
		];
	}
}
