<?php

$content_types = [
	'' => __('notifications.none'),
	'content-url' => __('forms.url'),
	'content-file' => __('forms.file')
];

$fields_basic = [
	[
		'label' => __('forms.title'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'title',
			'name' => 'title',
			'type' => 'text',
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('notifications.scheduled-at-optional'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'scheduled_at',
			'name' => 'scheduled_at',
			'type' => 'text',
			'class' => 'js-datetimepicker datetimepicker-input',
			'data-toggle' => 'datetimepicker',
			'data-target' => '#scheduled_at',
			'placeholder' => __('forms.now'),
			'readonly' => true
		]
	],
	[
		'label' => __('forms.body'),
		'tag' => 'textarea',
		'attributes' => [
			'id' => 'body',
			'name' => 'body',
			'maxlength' => 2000,
			'rows' => 10,
			'required' => true
		]
	]
];

$fields_radius = [
	[
		'label' => __('notifications.radius'),
		'tag' => 'input',
		'group' => [
			'right' => __('notifications.meters')
		],
		'attributes' => [
			'id' => 'radius',
			'name' => 'radius',
			'type' => 'number',
			'min' => 1,
			'data-map-radius-change' => 'location'
		]
	]
];

$fields_location = [
	[
		'label' => __('notifications.countries'),
		'tag' => 'select',
		'attributes' => [
			'id' => 'notifications-countries',
			'name' => 'countries[]',
			'multiple' => true,
			'data-api' => route('api.location.countries'),
			'data-token' => auth()->user()->token()
		]
	],
	[
		'label' => __('notifications.radius-location'),
		'tag' => 'map',
		'base_name' => 'location',
		'extra_fields' => $fields_radius
	]
];

$fields_attachments = [
	[
		'label' => __('notifications.content-type'),
		'tag' => 'radio-inline',
		'options' => $content_types,
		'selected' => '',
		'attributes' => [
			'class' => 'notifications-content-type',
			'type' => 'radio',
			'name' => 'content_type'
		]
	],
	[
		'label' => __('forms.url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'content-url',
			'name' => 'url',
			'type' => 'url',
			'maxlength' => 1000,
			'disabled' => true
		]
	],
	[
		'label' => __('forms.file'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'content-file',
			'name' => 'file',
			'type' => 'file',
			'disabled' => true
		]
	]
];

?>

@extends('layouts.master')

@push('scripts')
	<script defer src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_api_key') }}&callback=initMaps"></script>
@endpush

@section('content')
	@include('layouts.single_header', ['title' => __('notifications.title-s'), 'icon' => 'fa fa-broadcast-tower'])
	<form class="form form-notify" action="{{ route('notifications.store') }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		@csrf
		<div class="card-body">
			<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist" data-active-tab="tab">
				<li class="nav-item">
					<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-info"></i></span>
						<span class="nav-text">{{ __('settings.menu-basic') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#btabs-location" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-map-marker-alt"></i></span>
						<span class="nav-text">{{ __('notifications.location') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#btabs-media" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-link"></i></span>
						<span class="nav-text">{{ __('notifications.menu-file') }}</span>
					</a>
				</li>
			</ul>
			<div class="tab-content mt-5">
				<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>
				<div class="tab-pane fade" id="btabs-location" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_location])
				</div>
				<div class="tab-pane fade" id="btabs-media" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_attachments])
				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>
@endsection