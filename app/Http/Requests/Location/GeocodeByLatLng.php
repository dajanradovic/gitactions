<?php

namespace App\Http\Requests\Location;

use Illuminate\Foundation\Http\FormRequest;

class GeocodeByLatLng extends FormRequest
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
			'lat' => ['required', 'numeric', 'min:-90', 'max:90'],
			'lng' => ['required', 'numeric', 'min:-180', 'max:180'],
		];
	}
}
