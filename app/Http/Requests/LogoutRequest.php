<?php

namespace App\Http\Requests;

use App\Models\PushDevice;
use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
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
			'device_id' => ['nullable', 'string', 'max:128', 'exists:' . PushDevice::class],
		];
	}
}
