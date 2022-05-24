<?php

use App\Models\Setting;

$form_action = route('discounts.store-above-set-price-discount');
$actions = null;

if ($discount->exists) {
	$form_action = route('discounts.update-above-set-price-discount', $discount->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['discounts.remove' => $discount->id]
		]
	];
}

$fields_basic = [
	[
		'label' => __('forms.title'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'title',
			'name' => 'title',
			'type' => 'text',
			'value' => $discount->title,
			'maxlength' => 100,
			'required' => true,
			'autofocus' => true,
		]
	],
	[
		'label' => __('discounts.amount'),
		'tag' => 'input',
		'group' => [
			'right' => '% / ' . Setting::getMainCurrency()
		],
		'attributes' => [
			'id' => 'amount',
			'name' => 'amount',
			'type' => 'number',
			'value' => $discount->amount,
			'min' => 0,
			'required' => true
		]
	],
	[
		'label' => __('discounts.period-from'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'period_from',
			'name' => 'period_from',
			'type' => 'text',
			'value' => $discount->period_from ? formatLocalTimestamp($discount->period_from, 'd-m-Y') : null,
			'class' => 'js-datepicker datepicker-input',
			'data-toggle' => 'datepicker',
			'data-target' => '#period_from',
			'placeholder' => __('discounts.none'),
			'readonly' => true
		]
	],
	[
		'label' => __('discounts.period-to'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'period_to',
			'name' => 'period_to',
			'type' => 'text',
			'value' => $discount->period_to ? formatLocalTimestamp($discount->period_to, 'd-m-Y') : null,
			'class' => 'js-datepicker datepicker-input',
			'data-toggle' => 'datepicker',
			'data-target' => '#period_to',
			'placeholder' => __('discounts.none'),
			'readonly' => true
		]
	],
	[
		'label' => __('discounts.is-percentage'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'is_percentage',
			'name' => 'is_percentage',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $discount->is_percentage
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
			'checked' => $discount->active
		]
	],
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('discounts.title-above-set-price-s'), 'icon' => 'fa fa-edit', 'actions' => $actions, 'updated_at' => $discount->updated_at])
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
			</ul>
			<div class="tab-content mt-5">
				<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('discounts.above-set-price-discount-info')])
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>

	@include('layouts.modals.activity')
@endsection
