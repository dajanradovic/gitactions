<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
			'email' => ['required', 'email', 'max:50', 'exists:' . User::class],
			'password' => ['required', 'string', 'confirmed', new Password],
			'token' => ['required', 'string']
		];
	}
}
