<?php

namespace App\Http\Requests;

use App\Models\Filter;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFilter extends FormRequest
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
			'active' => ['boolean'],
			'required' => ['boolean'],
			'searchable' => ['boolean'],
			'type' => ['nullable', 'string', Rule::in(Filter::getAvailableFilterTypes())],
			'min' => ['nullable', 'integer', 'min:0', 'lte:max'],
			'max' => ['nullable', 'integer', 'min:0', 'gte:min'],
			'step' => ['nullable', 'numeric', 'min:0'],
			'value' => ['nullable', 'required_if:type,' . Filter::FILTER_TYPE_SELECT, 'string', 'max:500'],
			'value_en' => ['nullable', 'required_if:type,' . Filter::FILTER_TYPE_SELECT, 'string', 'max:500'],
			'message' => ['nullable', 'string', 'max:100'],
			'message_en' => ['nullable', 'string', 'max:100'],
			'display_label' => ['nullable', 'max:50', 'string'],
			'display_label_en' => ['nullable', 'max:50', 'string'],

		];
	}
}
