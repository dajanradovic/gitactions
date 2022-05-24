<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
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
		$user = $this->user();

		return array_replace([
			'name' => ['required', 'string', 'max:50'],
			'email' => ['required', 'max:50', 'email', 'unique:' . ($user ? $user::class : User::class) . ',email,' . ($user->id ?? '')],
			'allow_push_notifications' => ['boolean'],
			'surname' => ['required', 'string', 'max:80'],
			'oib' => ['nullable', 'digits_between:1, 30'],
			'date_of_birth' => ['nullable', 'date'],
			'company_name' => ['nullable', 'string', 'max:100'],
			'newsletter' => ['boolean'],
		], $user?->user->updateProfileValidationRules($this) ?? []);
	}
}
