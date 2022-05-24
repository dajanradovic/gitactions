<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AssignRoleToUsers extends FormRequest
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
			'role_id' => ['nullable', 'uuid', 'exists:' . Role::class . ',id'],
			'values' => ['required', 'array'],
			'values.*' => ['required', 'uuid'],
		];
	}
}
