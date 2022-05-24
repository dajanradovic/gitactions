<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResendEmailVerification extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return !$this->user()->hasVerifiedEmail();
	}

	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
		];
	}
}
