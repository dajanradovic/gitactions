<?php

$oauth_driver ??= null;
$name = $email = $avatar = $oauth_id = '';

$locales = config('custom.locales');
sort($locales);
$all_locales = [];

foreach ($locales as $value) {
	$all_locales[$value] = strtoupper($value);
}

if (isset($oauth_user)) {
	$oauth_id = $oauth_user->getId();
	$name = $oauth_user->getName();
	$email = strtolower($oauth_user->getEmail());
	$avatar = $oauth_user->getAvatar();
}

$fields = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $name,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('forms.email'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'email',
			'name' => config('fortify.email', 'email'),
			'type' => 'email',
			'value' => $email,
			'maxlength' => 50,
			'required' => true
		]
	],
	[
		'label' => __('forms.timezone'),
		'tag' => 'select',
		'options' => renderTimezones(),
		'selected' => setting('timezone'),
		'attributes' => [
			'id' => 'timezone',
			'name' => 'timezone',
			'required' => true
		]
	],
	[
		'label' => __('forms.locale'),
		'tag' => 'select',
		'condition' => count($all_locales) > 1,
		'options' => $all_locales,
		'selected' => config('app.locale'),
		'attributes' => [
			'id' => 'locale',
			'name' => 'locale',
			'required' => true
		]
	],
	[
		'label' => __('forms.password'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'password',
			'name' => 'password',
			'type' => 'password',
			'minlength' => setting('min_pass_len'),
			'required' => true
		]
	],
	[
		'label' => __('forms.confirm-password'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'password_confirmation',
			'name' => 'password_confirmation',
			'type' => 'password',
			'minlength' => setting('min_pass_len'),
			'required' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'name' => 'oauth_driver',
			'type' => 'hidden',
			'value' => $oauth_driver
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'name' => 'oauth_id',
			'type' => 'hidden',
			'value' => $oauth_id
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'name' => 'avatar',
			'type' => 'hidden',
			'value' => $avatar
		]
	]
];

?>

@extends('layouts.public')

@section('title', __('auth.register.title'))

@section('content')
	<div class="login login-2 login-signin-on d-flex flex-row-fluid">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url({{ asset('img/login.jpg') }});">
			<div class="login-form text-center p-7 position-relative overflow-hidden">
				<!--begin::Login Header-->
				@if(empty($avatar = old('avatar', $avatar)))
					<div class="d-flex flex-center mb-5">
						<a href="{{ route('home') }}">
							<img src="{{ asset('img/logo-black.png') }}" class="max-h-100px" alt="Logo">
						</a>
					</div>
				@else
					<div class="symbol symbol-150 mb-5">
						<img src="{{ $avatar }}" alt="{{ $name }}">
					</div>
				@endif
				<!--end::Login Header-->

				<!--begin::Body-->
				<div class="login-signin">
					<!--begin::Signin-->
					<div class="mb-10">
						<h3>{{ __('auth.register.title') }}</h3>
						<div class="text-muted font-weight-bold">
							<small>{{ __('global.powered-by') }} <a href="{{ config('custom.dev_url') }}" rel="author" target="_blank">{{ config('custom.dev_name') }}</a></small>
						</div>
					</div>

					<form class="form text-left" action="{{ route('register') }}" method="post" autocomplete="off">
						@csrf

						@include('layouts.forms.generate_form_fields', ['fields' => $fields])

						<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
							<a href="{{ route('login') }}" class="btn btn-secondary btn-elevate">
								<i class="fa fa-chevron-left"></i> {{ __('global.back') }}
							</a>

							<button type="submit" class="btn btn-primary btn-elevate">
								<i class="fa fa-sign-in-alt"></i> {{ __('auth.register.signup') }}
							</button>
						</div>
					</form>

					@include('layouts.social_logins')
				</div>
			</div>
		</div>
	</div>
@endsection