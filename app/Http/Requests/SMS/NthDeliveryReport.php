<?php

namespace App\Http\Requests\SMS;

use Illuminate\Foundation\Http\FormRequest;

class NthDeliveryReport extends FormRequest
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
			'statusText' => ['nullable', 'required_without:status.code', 'string', 'max:20'],
			'status.code' => ['nullable', 'required_without:statusText', 'string', 'max:20']
		];
	}
}
