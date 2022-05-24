<?php

namespace App\Http\Requests;

use App\Rules\SafeWebp;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatar extends FormRequest
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
		$maxUploadSize = $this->user()?->getStorageMaxUploadSize() ?? setting('max_upload_size');

		return [
			'avatar' => ['image', 'dimensions:ratio=1', 'max:' . $maxUploadSize / 1024, new SafeWebp],
		];
	}
}
