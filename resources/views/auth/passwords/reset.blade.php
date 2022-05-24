<?php

$fields = [
	[
		'tag' => 'input',
		'attributes' => [
			'name' => config('fortify.email', 'email'),
			'type' => 'email',
			'placeholder' => __('forms.email'),
			'value' => $request->email ?? '',
			'maxlength' => 50,
			'required' => true
		]
	],
	[
		'tag' => 'input',
		'attributes' => [
			'name' => 'password',
			'type' => 'password',
			'placeholder' => __('forms.password'),
			'minlength' => setting('min_pass_len'),
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'tag' => 'input',
		'attributes' => [
			'name' => 'password_confirmation',
			'type' => 'password',
			'placeholder' => __('forms.confirm-password'),
			'minlength' => setting('min_pass_len'),
			'required' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'name' => 'token',
			'type' => 'hidden',
			'value' => $request->token ?? ''
		]
	]
];

?>

@extends('layouts.public', ['seo' => false])

@section('title', __('auth.reset.title'))

@section('content')
	<div class="login login-2 login-signin-on d-flex flex-row-fluid">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url({{ asset('img/login.jpg') }});">
			<div class="login-form text-center p-7 position-relative overflow-hidden">
				<!--begin::Login Header-->
				<div class="d-flex flex-center mb-5">
					<a href="{{ route('home') }}">
						<img src="{{ asset('img/logo-black.png') }}" class="max-h-100px" alt="Logo">
					</a>
				</div>
				<!--end::Login Header-->

				<!--begin::Body-->
				<div class="login-signin">
					<div class="mb-10">
						<h3>{{ __('auth.reset.title') }}</h3>
						<div class="text-muted font-weight-bold">
							<small>{{ __('global.powered-by') }} <a href="{{ config('custom.dev_url') }}" rel="author" target="_blank">{{ config('custom.dev_name') }}</a></small>
						</div>
					</div>

					<!--begin::Form-->
					<form class="form text-left" action="{{ route('password.update') }}" method="post">
						@csrf

						@include('layouts.forms.generate_form_fields', ['fields' => $fields])

						<!--begin::Action-->
						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<a href="{{ route('login') }}" class="btn btn-secondary btn-elevate">
								<i class="fa fa-chevron-left"></i> {{ __('global.back') }}
							</a>

							<button type="submit" class="btn btn-primary btn-elevate">
								<i class="fa fa-undo"></i> {{ __('forms.reset') }}
							</button>
						</div>
						<!--end::Action-->
					</form>
					<!--end::Form-->
				</div>
				<!--end::Body-->
			</div>
		</div>
	</div>
@endsection