<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;

class SearchTerm extends FormRequest
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
			'search' => ['nullable', 'string', 'max:50'],
			'tags' => ['array'],
			'tags.*' => ['required', 'uuid', 'exists:' . Tag::class . ',id'],
		];
	}
}
