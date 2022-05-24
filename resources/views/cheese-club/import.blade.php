<?php

$fields = [
	[
		'label' => __('products.choose-file'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'file',
			'name' => 'file',
			'type' => 'file',
			'accept' => '.csv',
			'required' => true
		]
	],
	[
		'label' => __('products.header-row-record'),
		'tag' => 'checkbox',
		'message' => __('products.skip-header-row'),
		'attributes' => [
			'id' => 'header_row',
			'name' => 'header_row',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => true
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('cheese-club.import'), 'icon' => 'fas fa-upload'])
	<form class="form form-notify" action="{{ route('cheese-club.import') }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		<div class="card-body">
			@csrf


			@include('layouts.forms.generate_form_fields', ['fields' => $fields])

		</div>
		@include('layouts.submit_button')
	</form>
@endsection
