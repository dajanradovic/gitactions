<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ChartData extends FormRequest
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
			'min_date' => ['required', 'date'],
			'max_date' => ['required', 'date', 'after:min_date'],
			'date_format' => ['required', 'string', Rule::in(['Y', 'Y-m', 'Y-m-d', 'Y-m-d H'])],
		];
	}
}
