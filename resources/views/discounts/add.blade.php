<?php

use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;

$form_action = route('discounts.store');
$actions = null;

if ($discount->exists) {
	$form_action = route('discounts.update', $discount->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['discounts.remove' => $discount->id]
		]
	];
}

$all_categories = [];

foreach ($categories as $row) {
	$all_categories[$row->id] = $row->name;
}

$all_products = [];

foreach ($products as $row) {
	$all_products[$row->id] = $row->name;
}

function generateTableContent(Collection $collection, string $className): void
{
	$model = strtolower(explode('\\', $className)[2] ?? null);
	$html = '';

	$collection->where('item_type', $className)->values()->each(function ($item, $key) use (&$html, $model) {
		$html .= '<tr><td>' . $key + 1 . '</td><td>' . $item->{$model}->name . '</td></tr>';
	});

	if (!$html) {
		return;
	}

	echo "
		<table class='table table-sm'>
		<thead>
			<tr>
				<th scope='col'>#</th>
				<th scope='col'>Name</th>
			</tr>
		</thead>
		<tbody id={$className}>
			{$html}
		</tbody>
		</table>";
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
		'label' => __('discounts.add-up-with-other-discounts'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'add_up_with_other_discounts',
			'name' => 'add_up_with_other_discounts',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $discount->add_up_with_other_discounts
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

$fields_categories_under_discount = [
	[
		'label' => __('discounts.pick-categories'),
		'tag' => 'select',
		'options' => $all_categories,
		'selected' => $discount->getDiscountItemsIds(Category::class),
		'attributes' => [
			'id' => 'categories',
			'name' => 'categories[]',
			'multiple' => true
		]
	]
];

$fields_products_under_discount = [
	[
		'label' => __('discounts.pick-products'),
		'tag' => 'select',
		'options' => $all_products,
		'selected' => $discount->getDiscountItemsIds(Product::class),
		'attributes' => [
			'id' => 'products',
			'name' => 'products[]',
			'multiple' => true
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('discounts.title-s'), 'icon' => 'fa fa-edit', 'actions' => $actions, 'updated_at' => $discount->updated_at])
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
					<a href="#btabs-products-under-discount" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-image"></i></span>
						<span class="nav-text">{{ __('discounts.products-under-discount') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#btabs-categories-under-discount" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-image"></i></span>
						<span class="nav-text">{{ __('discounts.categories-under-discount') }}</span>
					</a>
				</li>
			</ul>
			<div class="tab-content mt-5">
				<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('discounts.basic-discount-info')])
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>
				<div class="tab-pane fade" id="btabs-products-under-discount" role="tabpanel">
					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('discounts.products-under-discounts-info')])
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_products_under_discount])
					@php
						generateTableContent($discount->items, Product::class);
					@endphp

				</div>
				<div class="tab-pane fade" id="btabs-categories-under-discount" role="tabpanel">
					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('discounts.categories-under-discounts-info')])
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_categories_under_discount])
					@php
						generateTableContent($discount->items, Category::class);
					@endphp
				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>

	@include('layouts.modals.activity')
@endsection
