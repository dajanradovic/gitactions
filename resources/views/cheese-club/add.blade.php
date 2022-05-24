<?php

$form_action = route('cheese-club.store');
$actions = null;

if ($cheese_club->exists) {
	$form_action = route('cheese-club.update', $cheese_club->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['cheese-club.remove' => $cheese_club->id]
		]
	];
}

$club_type_list = [null => __('global.none')];

foreach ($cheese_club_types as $key => $row) {
	$club_type_list[$key] = \App\Models\CheeseClub::getTypeName($row);
}

$fields_basic = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $cheese_club->name,
			'maxlength' => 50,
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
			'value' => $cheese_club->surname,
			'maxlength' => 80,
		]
	],
	[
		'label' => __('forms.email'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'email',
			'name' => 'email',
			'type' => 'email',
			'value' => $cheese_club->email,
			'maxlength' => 50,
			'autocomplete' => 'off'
		]
	],
	[
		'label' => __('cheese-club.club-type'),
		'tag' => 'select',
		'options' => $club_type_list,
		'selected' => $cheese_club->club_type,
		'attributes' => [
			'id' => 'club_type',
			'name' => 'club_type',
			'maxlength' => 80,
		]
	],
	[
		'label' => __('customers.date-of-birth'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'date_of_birth',
			'name' => 'date_of_birth',
			'type' => 'text',
			'value' => $cheese_club->date_of_birth ? formatLocalTimestamp($cheese_club->date_of_birth, 'd-m-Y') : null,
			'class' => 'js-datepicker-no-min datepicker-input',
			'data-toggle' => 'datepicker',
			'data-target' => '#date_of_birth',
		]
	],
	[
		'label' => __('cheese-club.points'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'points',
			'name' => 'points',
			'type' => 'number',
			'step' => 1,
			'value' => $cheese_club->points ?? 0,
			'min' => 0,
		]
	],
	[
		'label' => __('cheese-club.card-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'card_number',
			'name' => 'card_number',
			'type' => 'text',
			'value' => $cheese_club->card_number,
		]
	],
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('cheese-club.title-s'), 'icon' => 'fa fa-edit', 'actions' => $actions, 'updated_at' => $cheese_club->updated_at])
	<form class="form form-notify"  action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		@csrf
		<div class="card-body">
			<div class="tab-content mt-5">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
			</div>
		</div>
		@include('layouts.submit_button')
	</form>

	@include('layouts.modals.activity')
@endsection
