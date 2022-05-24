<?php

namespace App\Http\Requests;

use App\Rules\ValidateFilter;
use Illuminate\Foundation\Http\FormRequest;

class FilterProduct extends FormRequest
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
			'sort_by' => ['nullable', 'string', 'in:asc,desc'],
			'sort_by_field' => ['nullable', 'string', 'in:name,price,rating'],
			'price_min' => ['nullable', 'required_with:price_max', 'integer', 'lt:price_max', 'min:1'],
			'price_max' => ['nullable', 'required_with:price_min', 'integer', 'gt:price_min', 'min:1'],
			'category' => ['nullable', 'uuid', 'exists:categories,id'],
			'volume' => ['nullable', 'numeric', 'min:0'],
			'search' => ['nullable', 'string', 'max:50'],
			'lang' => ['nullable', 'string', 'size:2', 'in:hr,en'],
			'per_page' => ['nullable', 'integer', 'min:1'],
			'filters' => ['array'],
		//	'filters.*' => [new ValidateFilter],
			'adult_only' => ['boolean']

		];
	}
}
