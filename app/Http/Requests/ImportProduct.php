<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportProduct extends FormRequest
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
			'file' => ['required', 'file', 'mimes:csv,txt', 'max:' . setting('max_upload_size') / 1024],
			'header_row' => ['boolean'],
		];
	}
}
