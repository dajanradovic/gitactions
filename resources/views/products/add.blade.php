<?php

use App\Models\Product;

$form_action = route('products.update', $product->id);

$actions = [
	[
		'type' => 'remove',
		'action' => ['products.remove' => $product->id]
	]
];

$token = getUser()->token();

$list_categories = ['' => '-'];

foreach ($categories as $row) {
	$parents = $row->ancestors->reverse()->pluck('name')->implode(' > ');
	$list_categories[$row->id] = $parents ? $parents . ' > ' . $row->name : $row->name;
}

$fields_basic = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $product->name,
			'maxlength' => 100,
			'required' => true,
			'disabled' => true,
		]
	],
	[
		'label' => __('products.description'),
		'tag' => 'textarea',
		'value' => $product->description,
		'attributes' => [
			'id' => 'description',
			'name' => 'description',
			'maxlength' => 500,
			'rows' => 5,
		]
	],
	[
		'label' => __('products.price'),
		'tag' => 'input',
		'group' => [
			'right' => setting('currency_code')
		],
		'attributes' => [
			'id' => 'price',
			'name' => 'price',
			'type' => 'number',
			'value' => $product->price,
			'disabled' => true
		]
	],
	[
		'label' => __('products.quantity'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'quantity',
			'name' => 'quantity',
			'value' => $product->quantity,
			'type' => 'number',
			'disabled' => true
		]
	],
	[
		'label' => __('products.code'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'code',
			'name' => 'code',
			'value' => $product->code,
			'type' => 'string',
			'disabled' => true

		]
	],
	[
		'label' => __('products.harvest'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'harvest',
			'name' => 'harvest',
			'value' => $product->harvest,
			'type' => 'text',
			'min' => 0,
			'max' => 10

		]
	],
	[
		'label' => __('products.piktograms'),
		'tag' => 'select',
		'options' => Product::PIKTOGRAMS,
		'selected' => $product->piktograms,
		'attributes' => [
			'id' => 'piktograms',
			'name' => 'piktograms[]',
			'multiple' => true
		]
	],
	[
		'label' => __('products.sort-number'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'sort_number',
			'name' => 'sort_number',
			'value' => $product->sort_number ?? 0,
			'type' => 'number',
			'min' => 0,
			'max' => 100

		]
	],
	[
		'label' => __('products.variant-label'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'variant_label',
			'name' => 'variant_label',
			'type' => 'text',
			'value' => $product->variant_label,
			'maxlength' => 50,
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
			'checked' => $product->active
		]
	],
	[
		'label' => __('products.gratis'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'gratis',
			'name' => 'gratis',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $product->gratis
		]
	],
	[
		'label' => __('products.unavailable'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'unavailable',
			'name' => 'unavailable',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $product->unavailable
		]
	]
];

$fields_filter_template = [
	[
		'label' => __('forms.name'),
		'tag' => 'input'
	]
];

$fields_basic_english = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name_en',
			'name' => 'name_en',
			'type' => 'text',
			'value' => $product->getTranslation('name', 'en'),
			'maxlength' => 100,
		]
	],
	[
		'label' => __('products.description'),
		'tag' => 'textarea',
		'value' => $product->getTranslation('description', 'en'),
		'attributes' => [
			'id' => 'description_en',
			'name' => 'description_en',
			'maxlength' => 500,
			'rows' => 10
		]
	],
	[
		'label' => __('products.variant-label'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'variant_label_en',
			'name' => 'variant_label_en',
			'type' => 'text',
			'value' => $product->getTranslation('variant_label', 'en'),
			'maxlength' => 50,
		]
	],
];

$fields_category_filters = [
	[
		'label' => __('products.category'),
		'tag' => 'select',
		'options' => $list_categories,
		'selected' => $product->category_id,
		'attributes' => [
			'id' => 'category_id',
			'name' => 'category_id',
			'data-generate-filters' => '',
			'data-filters-api' => route('api.categories.filters'),
			'data-filter-values-api' => route('api.products.filters'),
			'data-token' => $token,
			'data-category-id' => $product->category_id,
			'data-product-id' => isset($product) ? $product->id : null
		]
	],
];

$fields_media = [
	[
		'label' => __('forms.image'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'images',
			'name' => 'images[]',
			'type' => 'file',
			'accept' => 'image/*',
			'data-media-gallery-id' => 'media-gallery'
		]
	]
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.single_header', ['title' => __('products.title-s'), 'icon' => 'fas fa-boxes', 'actions' => $actions, 'updated_at' => $product->updated_at])
    <form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist">
                <li class="nav-item">
                    <a href="#btabs-basic" class="nav-link active" data-toggle="tab">
                        <span class="nav-icon"><i class="fa fa-info"></i></span>
                        <span class="nav-text">{{ __('settings.menu-basic') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#btabs-basic-en" class="nav-link" data-toggle="tab">
                        <span class="nav-icon"><i class="fa fa-info"></i></span>
                        <span class="nav-text">{{ __('settings.menu-basic') . ' - ' .  __('translations.en') }}</span>
                    </a>
                </li>
				<li class="nav-item">
					<a href="#btabs-filters" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fas fa-filter"></i></span>
						<span class="nav-text">{{ __('filters.category-and-filters') }}</span>
					</a>
				</li>
				<li class="nav-item">
					<a href="#btabs-variants" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fas fa-tags"></i></span>
						<span class="nav-text">{{ __('products.variants') }}</span>
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
                    @include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
                </div>
                <div class="tab-pane fade show" id="btabs-basic-en" role="tabpanel">
                    @include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('translations.en-input')])
                    @include('layouts.forms.generate_form_fields', ['fields' => $fields_basic_english])
                </div>
				<div class="tab-pane fade" id="btabs-filters" role="tabpanel">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_category_filters])
					<div id="filters-content"></div>
				</div>
				<div class="tab-pane fade" id="btabs-variants" role="tabpanel">
					<p id="no-variants" class="{{!$variants || !old('variants') ? 'd-none' : ''}}">{{__('products.no-variants')}}</id>
					<div id="variants-section" data-variants="{{ json_encode(old('variants'), true) }}"
											   data-variantsprice="{{ json_encode(old('variants_price'), true) }}"
											   data-variantsmeasure="{{ json_encode(old('variants_measure'), true) }}"
											   data-variantsweight="{{ json_encode(old('variants_weight'), true) }}"
											   data-variantsen="{{ json_encode(old('variants_en'), true) }}"
											   data-existingvariants="{{ json_encode($variants, true) }}"

											   data-variantsLabel="{{__('products.variant-title')}}"
											   data-priceLabel="{{__('products.variant-price')}}"
											   data-measureLabel="{{__('products.variant-measure')}}"
											   data-weightLabel="{{__('products.variant-weight')}}"
											   data-variantsLabelEn="{{__('products.variant-title-en')}}"
											   >

					</div>
					<button class="btn btn-sm btn-primary" id="add-variant">Add variant</button>
					<button class="btn btn-sm btn-danger" id="delete-variant">Delete variant</button>

				</div>

                <div class="tab-pane fade" id="btabs-media" role="tabpanel">
                    @include('layouts.forms.generate_gallery_fields', [
                        'data' => [
                            'model' => $product,
                            'fields' => $fields_media,
                            'collection' => 'images',
                            'gallery_id' => 'media-gallery'
                        ]
                    ])
                </div>
			 </div>
            @csrf
        </div>
        @include('layouts.submit_button')
    </form>

	<template id="filter-list-template">
	@include('layouts.forms.generate_form_fields', ['fields' => $fields_filter_template])

	</template>
@endsection
