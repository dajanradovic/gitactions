<?php

$form_action = route('delivery-prices.update', $deliveryPrice->id);

$fields_basic = [
	[
		'label' => __('delivery-prices.country'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'country_code',
			'name' => 'country_code',
			'type' => 'text',
			'value' => $deliveryPrice::getCountryFullName($deliveryPrice->country_code),
			'disabled' => true
		]
	],
	[
		'label' => __('delivery-prices.delivery-service'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'delivery_service',
			'name' => 'delivery_service',
			'type' => 'text',
			'value' => $deliveryPrice->delivery_service,
			'disabled' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_2'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_2_kg',
			'name' => 'up_to_2_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_2_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_5'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_5_kg',
			'name' => 'up_to_5_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_5_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_10'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_10_kg',
			'name' => 'up_to_10_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_10_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_15'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_15_kg',
			'name' => 'up_to_15_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_15_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_20'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_20_kg',
			'name' => 'up_to_20_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_20_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_25'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_25_kg',
			'name' => 'up_to_25_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_25_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.up_to_32'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'up_to_32_kg',
			'name' => 'up_to_32_kg',
			'type' => 'number',
			'value' => $deliveryPrice->up_to_32_kg,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('delivery-prices.additional-costs'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'additional_costs',
			'name' => 'additional_costs',
			'type' => 'number',
			'value' => $deliveryPrice->additional_costs,
			'min' => 0,
			'required' => true
		]
	],

];

if ($deliveryPrice->country_code == $deliveryPrice::$homeCountry) {
	$fields_basic[] = [
		'label' => __('delivery-prices.islands-extra'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'islands_extra',
			'name' => 'islands_extra',
			'type' => 'number',
			'value' => $deliveryPrice->islands_extra,
			'min' => 0,
			'required' => true
		]
	];
}

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('delivery-prices.title-s'), 'icon' => 'fa fa-images', 'updated_at' => $deliveryPrice->updated_at])
	<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		<div class="card-body">
			@csrf
			@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
		</div>
		@include('layouts.submit_button')
	</form>
@endsection
