<?php

use App\Models\Category;
use Illuminate\Support\Collection;

$form_action = route('categories.store');
$actions = null;

if ($category->exists) {
	$form_action = route('categories.update', $category->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['categories.remove' => $category->id]
		]
	];
}

$all_categories = ['' => '-'];

foreach ($categories as $row) {
	$parents = $row->ancestors->reverse()->pluck('name')->implode(' > ');
	$all_categories[$row->id] = $parents ? $parents . ' > ' . $row->name : $row->name;
}

$all_filters = [];

foreach ($filters as $row) {
	$all_filters[$row->id] = $row->name;
}

function determineCountryValue(string $country, Category $category, ?Collection $vatRates = null): float|int
{
	if (is_array(old('countries'))) {
		return old('countries')[$country];
	}

	return $category->exists ? ($vatRates->firstWhere('country_code', $country)?->amount ?? 0) : 0;
}

$fields_basic = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $category->name,
			'maxlength' => 100,
			'required' => true,
			'autofocus' => true,
			'data-slug-target-id' => 'category-slug'
		]
	],
	[
		'label' => __('forms.slug'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'category-slug',
			'name' => 'slug',
			'type' => 'text',
			'value' => $category->slug,
			'maxlength' => 50,
			'required' => true,
			'data-slug-target-event' => 'onblur',
			'data-slug-target-id' => 'category-slug'
		]
	],
	// [
	// 	'label' => __('categories.parent'),
	// 	'tag' => 'select',
	// 	'options' => $all_categories,
	// 	'selected' => $category->category_id,
	// 	'attributes' => [
	// 		'id' => 'category_id',
	// 		'name' => 'category_id',
	// 	]
	// ],
	[
		'label' => __('categories.description'),
		'tag' => 'textarea',
		'value' => $category->description,
		'attributes' => [
			'id' => 'description',
			'name' => 'description',
			'maxlength' => 500,
			'rows' => 10,
		]
	],
	[
		'label' => __('filters.title-m'),
		'tag' => 'select',
		'options' => $all_filters,
		'selected' => $category->categoryFilters->pluck('filter_id')->all() ?? [],
		'attributes' => [
			'id' => 'selected_filters',
			'name' => 'selected_filters[]',
			'multiple' => true
		]
	],
	// [
	// 	'label' => __('categories.use-parent-filters'),
	// 	'tag' => 'checkbox',
	// 	'attributes' => [
	// 		'id' => 'use_parent_filters',
	// 		'name' => 'use_parent_filters',
	// 		'value' => 1,
	// 		'type' => 'checkbox',
	// 		'checked' => $category->use_parent_filters ?? true
	// 	]
	// ],
	[
		'label' => __('forms.active'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'active',
			'name' => 'active',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $category->active ?? true
		]
	],
	[
		'label' => __('categories.adult-only'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'adult_only',
			'name' => 'adult_only',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $category->adult_only ?? false
		]
	],
	[
		'label' => __('categories.extra-costs'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'extra_costs',
			'name' => 'extra_costs',
			'value' => $category->extra_costs ?? 0,
			'type' => 'integer',
			'min' => 0,
		]
	],
	[
		'label' => __('categories.group-code'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'group_code',
			'name' => 'group_code',
			'value' => $category->group_code,
			'type' => 'string',
			'minlegth' => 0,
			'maxlength' => 10
		]
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
			'value' => $category->getTranslation('name', 'en'),
			'maxlength' => 100,
			'required' => true,
			'autofocus' => true,
			'data-slug-target-id' => 'category-slug-en'
		]
	],
	[
		'label' => __('forms.slug'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'category-slug-en',
			'name' => 'slug_en',
			'type' => 'text',
			'value' => $category->getTranslation('slug', 'en'),
			'maxlength' => 50,
			'required' => true,
			'data-slug-target-event' => 'onblur',
			'data-slug-target-id' => 'category-slug-en'
		]
	],
	[
		'label' => __('categories.description'),
		'tag' => 'textarea',
		'value' => $category->getTranslation('description', 'en'),
		'attributes' => [
			'id' => 'description_en',
			'name' => 'description_en',
			'maxlength' => 500,
			'rows' => 10,
		]
	],
];

foreach (Category::getCountries() as $index => $country) {
	$fields_countries[] = [
		'label' => /* __("countries.$index") */ $index,
		'tag' => 'input',
		'group' => [
			'right' => '%'
		],
		'attributes' => [
			'class' => 'countries-item' . ($category->exists && $vatRates->firstWhere('country_code', $country)?->amount > 0 ? ' border border-primary' : ' border border-warning'),
			'name' => "countries[$country]",
			'type' => 'number',
			'value' => determineCountryValue($country, $category, $vatRates ?? null),
			'min' => 0,
			'max' => 100
		]
	];
}

$fields_media = [
	[
		'label' => __('forms.image'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'image',
			'name' => 'image',
			'type' => 'file',
			'accept' => 'image/*',
			'data-media-gallery-id' => 'media-gallery'
		]
	]
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.single_header', ['title' => __('categories.title-s'), 'icon' => 'fas fa-boxes', 'actions' => $actions, 'updated_at' => $category->updated_at])
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
					<a href="#btabs-vat" class="nav-link" data-toggle="tab">
						<span class="nav-icon"><i class="fa fa-image"></i></span>
						<span class="nav-text">{{ __('categories.vat') }}</span>
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
                    @include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('translations.hr-input')])
                    @include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
                </div>
                <div class="tab-pane fade show" id="btabs-basic-en" role="tabpanel">
                    @include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('translations.en-input')])
                    @include('layouts.forms.generate_form_fields', ['fields' => $fields_basic_english])
                </div>
                <div class="tab-pane fade" id="btabs-media" role="tabpanel">
                    @include('layouts.forms.generate_gallery_fields', [
                        'data' => [
                            'model' => $category,
                            'fields' => $fields_media,
                            'collection' => 'image',
                            'gallery_id' => 'media-gallery'
                        ]
                    ])
                </div>
				<div class="tab-pane fade col-md-6" id="btabs-vat" role="tabpanel">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_countries])
				</div>
            </div>
            @csrf
        </div>
        @include('layouts.submit_button')
    </form>
@endsection
