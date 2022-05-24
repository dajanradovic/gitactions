<?php

$form_action = route('users.store');
$actions = null;

if ($user->exists) {
	$form_action = route('users.update', $user->user->id);

	$activity_modal = json_encode([
		'api' => route('api.me.activities'),
		'token' => auth()->user()->token(),
		'title' => $user->name . ' > ' . __('activities.activity')
	]);

	$actions = [
		[
			'type' => 'test-api',
			'action' => ['api.me.get' => ['token' => $user->token()]]
		],
		[
			'type' => 'sessions',
			'action' => ['users.sessions' => $user->id]
		],
		[
			'type' => 'activity-modal',
			'action' => $activity_modal
		],
		[
			'type' => '2fa',
			'action' => ['users.2fa' => $user->id]
		],
		[
			'type' => 'remove',
			'action' => ['users.remove' => $user->user->id]
		]
	];
}

$locales = config('custom.locales');
sort($locales);
$all_locales = [];

foreach ($locales as $value) {
	$all_locales[$value] = strtoupper($value);
}

$all_roles = ['' => __('global.none')];

foreach ($roles as $row) {
	$all_roles[$row->id] = $row->name;
}

$fields = [
	[
		'label' => __('users.silent-login-link'),
		'condition' => $user->exists && $user->canViewRoute('home'),
		'tag' => 'input',
		'group' => [
			'right-button' => '<a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#silent_login_link" title="' . __('global.copy') . '"><i class="la la-copy"></i></a>'
		],
		'attributes' => [
			'id' => 'silent_login_link',
			'type' => 'url',
			'value' => $user->exists ? $user->getSilentLoginUrl() : '',
			'readonly' => true
		]
	],
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $user->name,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true
		]
	],
	[
		'label' => __('forms.email') . ' (' . __('forms.unique-label') . ')',
		'tag' => 'input',
		'attributes' => [
			'id' => 'email',
			'name' => 'email',
			'type' => 'email',
			'value' => $user->email,
			'maxlength' => 50,
			'required' => true
		]
	],
	[
		'label' => __('roles.title-s'),
		'condition' => $roles->isNotEmpty(),
		'tag' => 'select',
		'options' => $all_roles,
		'selected' => $user->exists ? $user->role_id : setting('registration_role_id'),
		'attributes' => [
			'id' => 'role_id',
			'name' => 'role_id'
		]
	],
	[
		'label' => __('forms.timezone'),
		'tag' => 'select',
		'options' => renderTimezones(),
		'selected' => $user->timezone ?? setting('timezone'),
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
		'selected' => $user->locale ?? config('app.locale'),
		'attributes' => [
			'id' => 'locale',
			'name' => 'locale',
			'required' => true
		]
	],
	[
		'label' => __('forms.password') . ' (' . __('forms.minimum-chars', ['n' => setting('min_pass_len')]) . ')',
		'tag' => 'input',
		'attributes' => [
			'id' => 'password',
			'name' => 'password',
			'type' => 'password',
			'minlength' => setting('min_pass_len')
		]
	],
	[
		'label' => __('forms.confirm-password'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'password_confirmation',
			'name' => 'password_confirmation',
			'type' => 'password',
			'minlength' => setting('min_pass_len')
		]
	],
	[
		'label' => __('users.allow-push-notifications'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'allow_push_notifications',
			'name' => 'allow_push_notifications',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $user->allow_push_notifications ?? true
		]
	],
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'active',
			'name' => 'active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $user->active ?? true
		]
	],
	[
		'label' => __('users.verified'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'verified',
			'name' => 'verified',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $user->hasVerifiedEmail()
		]
	]
];

$fields_media = [
	[
		'label' => __('users.avatar'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'avatar',
			'name' => 'avatar',
			'type' => 'file',
			'accept' => 'image/*',
			'data-media-gallery-id' => 'media-gallery'
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('users.title-s'), 'icon' => 'fa fa-user-edit', 'actions' => $actions, 'updated_at' => $user->updated_at])
	<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
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
					<a href="#btabs-media" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-image"></i></span>
						<span class="nav-text">{{ __('settings.menu-media') }}</span>
					</a>
				</li>
			</ul>
			<div class="tab-content mt-5">
				<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields])
				</div>
				<div class="tab-pane fade" id="btabs-media" role="tabpanel">
					@include('layouts.forms.generate_gallery_fields', [
						'data' => [
							'model' => $user,
							'fields' => $fields_media,
							'collection' => 'avatar',
							'gallery_id' => 'media-gallery'
						]
					])
				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>

	@include('layouts.modals.activity')
@endsection
