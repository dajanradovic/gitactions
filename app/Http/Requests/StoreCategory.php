<?php

namespace App\Http\Requests;

use App\Rules\SafeWebp;
use App\Services\MediaStorage;
use App\Rules\RecursiveCategoryRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategory extends FormRequest
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
			'category_id' => ['nullable', 'uuid', 'exists:categories,id', new RecursiveCategoryRule($this->id, $this->category_id)],
			'name' => ['required', 'string', 'max:100'],
			'name_en' => ['required', 'string', 'max:100'],
			'active' => ['boolean'],
			'adult_only' => ['boolean'],
			'use_parent_filters' => ['boolean'],
			'extra_costs' => ['nullable', 'numeric', 'min:0'],
			'selected_filters' => ['array'],
			'selected_filters.*' => ['required', 'uuid'],
			'description' => ['nullable', 'string', 'max:200'],
			'description_en' => ['nullable', 'string', 'max:200'],
			'image' => ['image', 'max:' . setting('max_upload_size') / 1024,  new SafeWebp],
			'countries' => ['array'],
			'countries.*' => ['numeric', 'min:0', 'max:50'],
			'group_code' => ['nullable', 'string', 'min:0', 'max:10']
		]);
	}
}
