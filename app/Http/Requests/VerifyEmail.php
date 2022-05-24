<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmail extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		$user = $this->getCurrentUser();

		if ($user->hasVerifiedEmail() && $this->wantsJson()) {
			return false;
		}

		return hash_equals((string) $this->route('hash'), sha1($user->getEmailForVerification()));
	}

	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
		];
	}

	protected function getCurrentUser(): MustVerifyEmail
	{
		return $this->route('id');
	}
}
