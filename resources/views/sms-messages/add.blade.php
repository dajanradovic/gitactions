<?php

$providers = [
	'' => '-',
	\App\Models\SmsMessage::PROVIDER_VONAGE => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_VONAGE),
	\App\Models\SmsMessage::PROVIDER_TWILIO => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_TWILIO),
	\App\Models\SmsMessage::PROVIDER_INFOBIP => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_INFOBIP),
	\App\Models\SmsMessage::PROVIDER_NTH => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_NTH),
	\App\Models\SmsMessage::PROVIDER_ELKS => __('sms-messages.providers.' . \App\Models\SmsMessage::PROVIDER_ELKS),
];

$fields = [
	[
		'label' => __('sms-messages.provider'),
		'tag' => 'select',
		'options' => $providers,
		'attributes' => [
			'id' => 'provider',
			'name' => 'provider',
			'required' => true
		]
	],
	[
		'label' => __('sms-messages.from-info'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'from',
			'name' => 'from',
			'type' => 'tel',
			'maxlength' => 20
		]
	],
	[
		'label' => __('sms-messages.to-info'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'to',
			'name' => 'to',
			'type' => 'tel',
			'maxlength' => 20,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('forms.body'),
		'tag' => 'textarea',
		'attributes' => [
			'id' => 'body',
			'name' => 'body',
			'maxlength' => 1600,
			'rows' => 5,
			'required' => true
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('sms-messages.title-s'), 'icon' => 'fa fa-comments'])
	<form class="form form-notify" action="{{ route('sms-messages.store') }}" method="post" autocomplete="off" id="main-form">
		<div class="card-body">
			@csrf
			@include('layouts.forms.generate_form_fields', ['fields' => $fields])
		</div>
		@include('layouts.submit_button')
	</form>
@endsection