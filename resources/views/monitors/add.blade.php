<?php

$form_action = route('monitors.store');
$actions = null;

if ($monitor->exists) {
	$form_action = route('monitors.update', $monitor->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['monitors.remove' => $monitor->id]
		]
	];
}

$methods = [
	'head' => 'HEAD',
	'get' => 'GET'
];

$fields = [
	[
		'label' => __('forms.url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'url',
			'name' => 'url',
			'type' => 'url',
			'value' => $monitor->url,
			'maxlength' => 1000,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('monitors.interval'),
		'tag' => 'input',
		'group' => [
			'right' => __('settings.minutes'),
		],
		'attributes' => [
			'id' => 'uptime_check_interval_in_minutes',
			'name' => 'uptime_check_interval_in_minutes',
			'type' => 'number',
			'value' => $monitor->uptime_check_interval_in_minutes ?? 5,
			'min' => 1,
			'required' => true
		]
	],
	[
		'label' => __('monitors.method'),
		'tag' => 'select',
		'options' => $methods,
		'selected' => $monitor->uptime_check_method,
		'attributes' => [
			'id' => 'uptime_check_method',
			'name' => 'uptime_check_method',
			'required' => true
		]
	],
	[
		'label' => __('monitors.cert-check'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'certificate_check_enabled',
			'name' => 'certificate_check_enabled',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $monitor->certificate_check_enabled ?? true
		]
	],
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'uptime_check_enabled',
			'name' => 'uptime_check_enabled',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $monitor->uptime_check_enabled ?? true
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('monitors.title-s'), 'icon' => 'fa fa-clock', 'actions' => $actions, 'updated_at' => $monitor->updated_at])
	<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form">
		<div class="card-body">
			@csrf
			@include('layouts.forms.generate_form_fields', ['fields' => $fields])
		</div>
		@include('layouts.submit_button')
	</form>
@endsection
