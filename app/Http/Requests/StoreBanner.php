<?php

namespace App\Http\Requests;

use App\Models\Banner;
use App\Rules\SafeWebp;
use App\Services\MediaStorage;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBanner extends FormRequest
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
			'type' => ['required', 'integer', Rule::in(Banner::getBannerTypeConstants())],
			'title' => ['required', 'string', 'max:50'],
			'subtitle' => ['nullable', 'string', 'max:50'],
			'order_column' => ['required', 'integer', 'min:0', 'max:255'],
			'url' => ['nullable', 'url', 'max:256'],
			'active' => ['boolean'],
			'image' => ['file', 'max:' . setting('max_upload_size') / 1024, new SafeWebp],
			'image_mobile' => ['file', 'max:' . setting('max_upload_size') / 1024, new SafeWebp],
		]);
	}
}
