<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
			'name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'email', 'max:50', 'unique:' . User::class],
			'password' => ['nullable', 'required_without:oauth_driver,oauth_token', 'string', 'confirmed', new Password],
			'oauth_driver' => ['nullable', 'string', 'max:20', 'required_with:oauth_token', 'required_without:password'],
			'oauth_token' => ['nullable', 'string', 'required_with:oauth_driver', 'required_without:password'],
			'surname' => ['required', 'string', 'max:80'],
			'oib' => ['nullable', 'digits_between:1, 30'],
			'date_of_birth' => ['nullable', 'date'],
			'company_name' => ['nullable', 'string', 'max:100'],
			'newsletter' => ['boolean'],
			'club_card' => ['boolean'],
		];
	}
}
