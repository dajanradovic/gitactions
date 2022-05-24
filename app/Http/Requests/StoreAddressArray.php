<?php

namespace App\Http\Requests;

use App\Models\Address;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressArray extends FormRequest
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
		$rule = [];

		foreach (Address::getTypes() as $type) {
			$request = request();

			$req_rule = 'nullable';

			if ($request->address_name[$type] || $request->country_code[$type] || $request->street[$type] || $request->city[$type] || $request->zip_code[$type] || $request->phone[$type]) {
				$req_rule = 'required';
			}

			$rule['address_name.' . $type] = [$req_rule, 'string', 'max:150'];
			$rule['country_code.' . $type] = [$req_rule, 'string', Rule::in(Address::getCountries())];
			$rule['street.' . $type] = [$req_rule, 'string', 'max:150'];
			$rule['city.' . $type] = [$req_rule, 'string', 'max:50'];
			$rule['zip_code.' . $type] = [$req_rule, 'string', 'max:20'];
			$rule['phone.' . $type] = ['nullable', 'digits_between:1,20'];
		}

		return $rule;
	}
}
