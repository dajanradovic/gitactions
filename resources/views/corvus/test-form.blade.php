<?php

$token = auth()->user()->token();
$form_action = 'https://test-wallet.corvuspay.com/checkout/';
$actions = $updated_at = null;
$require_complete = 'false';
$store_id = $corvusSignature = $currency = $cart = $payment_number = $language = '';
$cardholder_name = $cardholder_surname = $cardholder_city = $cardholder_address = $cardholder_zip_code = $cardholder_phone = $cardholder_email = $cardholder_country = '';
$order_number = '';
$amount = '';

$fields_basic = [
	[
		'label' => __('Version'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'version',
			'name' => 'version',
			'type' => 'text',
			'value' => '1.3',
		]
	],
	[
		'label' => __('settings.corvus-store-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'store_id',
			'name' => 'store_id',
			'type' => 'text',
			'value' => $store_id ? $store_id : setting('corvus_store_id'),
			'maxlength' => 10
		]
	],
	[
		'label' => __('required_complete'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'require_complete',
			'name' => 'require_complete',
			'type' => 'text',
			'value' => $require_complete,
		]
	],
	[
		'label' => __('Order number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'order_number',
			'name' => 'order_number',
			'type' => 'text',
			'value' => $order_number,
			'maxlength' => 36
		]
	],
	[
		'label' => __('Amount'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'amount',
			'name' => 'amount',
			'type' => 'text',
			'value' => $amount,
		]
	],
	[
		'label' => __('Signature'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'signature',
			'name' => 'signature',
			'type' => 'text',
			'value' => $corvusSignature,
		]
	],
	[
		'label' => __('Cart'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'cart',
			'name' => 'cart',
			'type' => 'text',
			'value' => $cart,
		]
	],
	[
		'label' => __('settings.corvus-currency'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'currency',
			'name' => 'currency',
			'type' => 'text',
			'value' => setting('corvus_currency'),
			'maxlength' => 3
		]
	],
	[
		'label' => __('Language'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'language',
			'name' => 'language',
			'type' => 'text',
			'value' => setting('corvus_language'),
			'maxlength' => 10
		]
	],
];
?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('Corvus Checkout'), 'icon' => 'fa fa-edit', 'actions' => $actions, 'updated_at' => $updated_at])
	<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		<div class="card-body">
			<div class="tab-content mt-5">
				<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>

			</div>
		</div>
		@include('layouts.submit_button')
	</form>

@endsection
