<?php

namespace App\Http\Requests;

use App\Rules\SafeWebp;
use App\Services\MediaStorage;
use Illuminate\Foundation\Http\FormRequest;

class StoreNotification extends FormRequest
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
			'body' => ['required', 'string', 'max:2000'],
			'url' => ['nullable', 'active_url', 'max:1000'],
			'countries' => ['array'],
			'countries.*' => ['required', 'string', 'size:2'],
			'radius' => ['nullable', 'required_with_all:location_lat,location_lng', 'integer', 'min:1'],
			'location_lat' => ['nullable', 'required_with:location_lng', 'numeric', 'min:-90', 'max:90'],
			'location_lng' => ['nullable', 'required_with:location_lat', 'numeric', 'min:-180', 'max:180'],
			'scheduled_at' => ['nullable', 'date'],
			'file' => ['file', 'max:' . setting('max_upload_size') / 1024, new SafeWebp]
		]);
	}
}
