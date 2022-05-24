<?php

namespace App\Http\Requests;

use App\Rules\SafeWebp;
use App\Rules\ValidateFilter;
use App\Services\MediaStorage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
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

			'name_en' => ['nullable', 'string', 'max:100'],
			'description' => ['nullable', 'max:500', 'string'],
			'description_en' => ['nullable', 'string', 'max:500'],
			'active' => ['boolean'],
			'gratis' => ['boolean'],
			'unavailable' => ['boolean'],
			'sort_number' => ['integer', 'min:0', 'max:100'],
			'harvest' => ['nullable', 'string', 'min:0', 'max:10'],
			'images' => ['array'],
			'images.*' => ['image', 'max:' . setting('max_upload_size') / 1024, new SafeWebp],
			'filters' => ['array'],
			'filters.*' => [new ValidateFilter],
			'piktograms' => ['array'],
			'variants' => ['array', 'required_with:variants_price,variants_measure, variants_en, variant_ids, variants_weight'],
			'variants.*' => ['required', 'string', 'max:100'],
			'variants_price' => ['array', 'required_with:variants,variants_measure, variants_en, variants_id, variants_weight'],
			'variants_price.*' => ['required', 'numeric', 'min:0'],
			'variants_measure' => ['array', 'required_with:variants_price,variants, variants_en, variant_ids, variants_weight'],
			'variants_measure.*' => ['required', 'numeric', 'min:0'],
			'variants_weight' => ['array', 'required_with:variants_price,variants, variants_en, variant_ids, variants_measure'],
			'variants_weight.*' => ['required', 'numeric', 'min:0'],
			'variants_en' => ['array', 'required_with:variants_price,variants_measure, variants, variants_ids, variants_weight'],
			'variant_ids' => ['array', 'required_with:variants_price,variants_measure, variants, variants_en, variants_weight'],
			'variants_ids.*' => ['required'],
			'variants_en.*' => ['required', 'string', 'max:100'],
			'variant_label' => ['nullable', 'string', 'max:50'],

		]);
	}
}
