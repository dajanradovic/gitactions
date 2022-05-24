<?php

$fields = [
	[
		'tag' => 'input',
		'attributes' => [
			'name' => config('fortify.email', 'email'),
			'type' => 'email',
			'placeholder' => __('forms.email'),
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'tag' => 'input',
		'attributes' => [
			'name' => 'password',
			'type' => 'password',
			'placeholder' => __('forms.password'),
			'minlength' => setting('min_pass_len'),
			'required' => true
		]
	]
];

?>

@extends('layouts.public')

@section('title', __('auth.login.title'))

@section('content')
	<div class="login login-2 login-signin-on d-flex flex-row-fluid">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url({{ asset('img/login.jpg') }});">

			@if(setting('registration_active'))
				<!--begin::Content header-->
				<div class="position-absolute top-0 right-0 text-right mt-5 mb-15 mb-lg-0 flex-column-auto justify-content-center py-5 px-10">
					<span class="font-weight-bold text-dark-50">{{ __('auth.register.account') }}</span>
					<a href="{{ route('register') }}" class="font-weight-bold ml-2">{{ __('auth.register.signup') }}</a>
				</div>
				<!--end::Content header-->
			@endif

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
						<h3>{{ __('auth.login.title') }}</h3>
						<div class="text-muted font-weight-bold">
							<small>{{ __('global.powered-by') }} <a href="{{ config('custom.dev_url') }}" rel="author" target="_blank">{{ config('custom.dev_name') }}</a></small>
						</div>
					</div>

					@if(session('status'))
						@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'success', 'text' => session('status')])
					@endif

					<!--begin::Form-->
					<form class="form text-left" action="{{ route('login') }}" method="post">
						@csrf

						@include('layouts.forms.generate_form_fields', ['fields' => $fields])

						<div class="form-group d-flex flex-wrap justify-content-between align-items-left">
							<div class="checkbox-inline">
								<label class="checkbox m-0 text-muted">
									<input type="checkbox" name="remember">
									<span></span>
									{{ __('auth.login.remember-me') }}
								</label>
							</div>
						</div>

						<!--begin::Action-->
						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<a href="{{ route('password.request') }}" class="text-muted text-hover-primary">{{ __('auth.login.forgot-password') }}</a>

							<button type="submit" class="btn btn-primary btn-elevate">
								<i class="fa fa-sign-in-alt"></i> {{ __('auth.login.title') }}
							</button>
						</div>
						<!--end::Action-->
					</form>
					<!--end::Form-->

					@include('layouts.social_logins')
				</div>
				<!--end::Body-->
			</div>
			<!--end::Content-->
		</div>
	</div>
@endsection