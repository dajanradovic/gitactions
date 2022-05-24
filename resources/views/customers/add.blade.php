<?php
$actions = null;

if ($user->exists) {
	$form_action = route('customers.update', $user->user->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['customers.remove' => $user->user->id]
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
		'label' => __('customers.surname'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'surname',
			'name' => 'surname',
			'type' => 'text',
			'value' => $user->user->surname,
			'maxlength' => 80,
			'required' => true
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
		'label' => __('customers.oib'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'oib',
			'name' => 'oib',
			'type' => 'text',
			'value' => $user->user->oib,
			'maxlength' => 30
		]
	],
	[
		'label' => __('customers.date-of-birth'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'date_of_birth',
			'name' => 'date_of_birth',
			'type' => 'text',
			'value' => $user->user->date_of_birth ? formatLocalTimestamp($user->user->date_of_birth, 'd-m-Y') : null,
			'class' => 'js-datepicker-no-min datepicker-input',
			'data-toggle' => 'datepicker',
			'data-target' => '#date_of_birth',
		]
	],
	[
		'label' => __('customers.company-name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'company_name',
			'name' => 'company_name',
			'type' => 'text',
			'value' => $user->user->company_name,
			'maxlength' => 100
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
		'label' => __('customers.newsletter'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'newsletter',
			'name' => 'newsletter',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $user->user->newsletter ?? true
		]
	],
	[
		'label' => __('customers.club-card'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'club_card',
			'name' => 'club_card',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $user->user->club_card ?? true
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
	@include('layouts.single_header', ['title' => __('customers.title-s'), 'icon' => 'fa fa-user-edit', 'actions' => $actions, 'updated_at' => $user->updated_at])
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
				<li class="nav-item">
					<a href="#btabs-address" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="far fa-address-book"></i></span>
						<span class="nav-text">{{ __('customers.menu-address') }}</span>
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
				<div class="tab-pane fade" id="btabs-address" role="tabpanel">
					<div class="container">
						<div class="row">
							@foreach (\App\Models\Address::getTypes() as $type)
									<?php
										$address = $addresses->where('type', $type)->first();
										$address_name = $country_code = $street = $city = $zip_code = $phone = null;

										if ($address) {
											$address_name = $address->name;
											$country_code = $address->country_code;
											$street = $address->street;
											$city = $address->city;
											$zip_code = $address->zip_code;
											$phone = $address->phone;
										}

										$fields_address = [
											[
												'label' => __('customers.address-name'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'address_name[' . $type . ']',
													'name' => 'address_name[' . $type . ']',
													'type' => 'text',
													'value' => $address_name ?? null,
													'maxlength' => 150,
												]
											],
											[
												'label' => __('customers.country-code'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'country_code[' . $type . ']',
													'name' => 'country_code[' . $type . ']',
													'type' => 'text',
													'value' => $country_code ?? null,
													'max' => 3
												]
											],
											[
												'label' => __('customers.street'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'street[' . $type . ']',
													'name' => 'street[' . $type . ']',
													'type' => 'text',
													'value' => $street ?? null,
													'maxlength' => 150,
												]
											],
											[
												'label' => __('customers.city'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'city[' . $type . ']',
													'name' => 'city[' . $type . ']',
													'type' => 'text',
													'value' => $city ?? null,
													'maxlength' => 50,
												]
											],
											[
												'label' => __('customers.zip_code'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'zip_code[' . $type . ']',
													'name' => 'zip_code[' . $type . ']',
													'type' => 'text',
													'value' => $zip_code ?? null,
													'maxlength' => 20,
												]
											],
											[
												'label' => __('customers.phone'),
												'tag' => 'input',
												'attributes' => [
													'id' => 'phone[' . $type . ']',
													'name' => 'phone[' . $type . ']',
													'type' => 'text',
													'value' => $phone ?? null,
													'maxlength' => 20,
												]
											],
										];

										$delivery_name = \App\Models\Address::getTypeName($type)
									?>
									<div class="col-sm">
										@include('layouts.forms.partials.generate_card', ['fields' => $fields_address, 'card_name' => $delivery_name])
									</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>

@endsection
