<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Address;
use App\Models\DeliveryPrice;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckIfCouponCodeIsApplicable;

class StoreOrder extends FormRequest
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

			'delivery' => ['required', 'integer', Rule::in(Order::deliveryOptions())],
			'shipping_provider' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string', Rule::in(DeliveryPrice::getShippingServices())],
			'items' => ['required', 'array'],
			'items.*.product_id' => ['required', 'uuid', 'exists:products,id'],
			'items.*.variant_id' => ['nullable', 'uuid', 'exists:product_variants,id', 'bail'],
			'items.*.quantity' => ['required', 'integer', 'min:0'],
			'coupon_code' => ['nullable', 'prohibits:points', 'string', 'max:10', 'exists:discounts,code', 'bail', new CheckIfCouponCodeIsApplicable],
			'points' => ['nullable', 'prohibits:coupon_code', 'integer', Rule::prohibitedIf(!getUser())],
			'store_id' => ['prohibited_if:delivery,' . Order::DELIVERY_SHIPPING, 'prohibited_if:payment_method,' . Order::PAYMENT_METHOD_POUZECE, Rule::requiredIf($this->delivery == Order::DELIVERY_PICKUP_IN_STORE), 'nullable', 'uuid', 'exists:stores,id'],
			'payment_method' => ['required', 'integer', Rule::in(Order::availablePaymentMethods())],
			'payment_card_provider' => [Rule::requiredIf($this->payment_method == Order::PAYMENT_METHOD_CARD), 'integer', Rule::in(Order::getCardProviders())],
			'email' => ['required', 'email', 'max:50'],

			'delivery_address' => ['array', Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING)],
			'delivery_address.name' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string:50'],
			'delivery_address.street' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string: 100'],
			'delivery_address.zip_code' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string:10'],
			'delivery_address.city' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string: 50'],
			'delivery_address.country_code' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'string', Rule::in(Address::getCountries())],
			'delivery_address.phone' => [Rule::requiredIf($this->delivery == Order::DELIVERY_SHIPPING), 'digits_between:1,20'],
			'delivery_address.note' => ['nullable', 'string', 'max:100'],

			'invoice_address' => ['array'],
			'invoice_address.name' => ['nullable', 'string:50', 'required_with:invoice_address.street,invoice_address.zip_code,invoice_address.city,invoice_address.country_code,invoice_address.oib'],
			'invoice_address.street' => ['nullable', 'string: 100', 'required_with:invoice_address.name,invoice_address.zip_code,invoice_address.city,invoice_address.country_code,invoice_address.oib'],
			'invoice_address.zip_code' => ['nullable', 'string:10', 'required_with:invoice_address.street,invoice_address.name,invoice_address.city,invoice_address.country_code,invoice_address.oib'],
			'invoice_address.city' => ['nullable', 'string: 50', 'required_with:invoice_address.street,invoice_address.zip_code,invoice_address.name,invoice_address.country_code,invoice_address.oib'],
			'invoice_address.country_code' => ['nullable', 'string', Rule::in(Address::getCountries()), 'required_with:invoice_address.street,invoice_address.zip_code,invoice_address.name,invoice_address.city,invoice_address.oib'],
			'invoice_address.oib' => ['nullable', 'digits_between:1, 30', 'required_with:invoice_address.street,invoice_address.zip_code,invoice_address.name,invoice_address.city,invoice_address.country_code'],

		];
	}
}
