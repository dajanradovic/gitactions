<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUptimeMonitor extends FormRequest
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
			'url' => ['required', 'active_url', 'max:1000'],
			'uptime_check_interval_in_minutes' => ['required', 'integer', 'min:1'],
			'uptime_check_method' => ['required', 'string', 'max:6'],
			'uptime_check_enabled' => ['boolean'],
			'certificate_check_enabled' => ['boolean']
		];
	}
}
