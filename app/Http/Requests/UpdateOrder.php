<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrder extends FormRequest
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
			'shipments' => ['array'],
			'shipments.*' => ['nullable', 'string', 'max:30'],
			'ready_for_pickup' => ['nullable', 'boolean'],
			'status' => ['integer', Rule::in(Order::getStatuses())],
			'payment_id' => ['nullable', 'string', 'min:0', 'max:50']
		];
	}
}
