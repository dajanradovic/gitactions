<?php

namespace App\Http\Requests;

use App\Models\SmsMessage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSmsMessage extends FormRequest
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
			'provider' => ['required', 'integer', Rule::in(SmsMessage::getAvailableProviders())],
			'from' => ['nullable', 'string', 'max:20'],
			'to' => ['required', 'string', 'max:20', 'phone:AUTO'],
			'body' => ['required', 'string', 'max:1600'],
		];
	}
}
