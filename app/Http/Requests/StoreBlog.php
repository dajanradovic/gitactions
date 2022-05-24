<?php

namespace App\Http\Requests;

use App\Rules\SafeWebp;
use App\Services\MediaStorage;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlog extends FormRequest
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
		return MediaStorage::mergeValidationRules([
			'title' => ['required', 'string', 'max:50'],
			'body' => ['required', 'string', 'max:5000'],
			'published_at' => ['nullable', 'date'],
			'tags' => ['nullable', 'string'],
			'image' => ['image', 'max:' . setting('max_upload_size') / 1024, new SafeWebp],
		]);
	}
}
