<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCheeseClub extends FormRequest
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
			'name' => ['nullable', 'string', 'max:50'],
			'surname' => ['nullable', 'string', 'max:80'],
			'club_type' => ['nullable', 'integer'],
			'date_of_birth' => ['nullable', 'date', 'max:50'],
			'points' => ['required', 'integer', 'min:0'],
			'card_number' => ['nullable', 'string'],
		];
	}
}
