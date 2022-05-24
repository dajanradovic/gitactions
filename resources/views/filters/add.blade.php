<?php

$token = getUser()->token();
$form_action = route('filters.store');
$actions = null;

if ($filter->exists) {
	$form_action = route('filters.update', $filter->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['filters.remove' => $filter->id]
		]
	];
}

$types = [
	\App\Models\Filter::FILTER_TYPE_TEXT => __('filters.type-text'),
	\App\Models\Filter::FILTER_TYPE_NUMBER => __('filters.type-number'),
	/*\App\Models\Filter::FILTER_TYPE_TEXTAREA => __('filters.type-textarea'),
	\App\Models\Filter::FILTER_TYPE_EMAIL => __('filters.type-email'),
	\App\Models\Filter::FILTER_TYPE_TEL => __('filters.type-tel'),
	\App\Models\Filter::FILTER_TYPE_URL => __('filters.type-url'),
	\App\Models\Filter::FILTER_TYPE_COLOR => __('filters.type-color'),
	\App\Models\Filter::FILTER_TYPE_RANGE => __('filters.type-range'),*/
	\App\Models\Filter::FILTER_TYPE_SELECT => __('filters.type-select'),
];

$fields_basic = [
	[
		'label' => __('forms.name'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'name',
			'name' => 'name',
			'type' => 'text',
			'value' => $filter->name,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true,
		]
	],
	[
		'label' => __('filters.display-label'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'display_label',
			'name' => 'display_label',
			'type' => 'text',
			'value' => $filter->display_label,
			'maxlength' => 50,
		]
	],
	[
		'label' => __('filters.help-message'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'message',
			'name' => 'message',
			'type' => 'text',
			'value' => $filter->message,
			'maxlength' => 100
		]
	],
	[
		'label' => __('filters.type'),
		'tag' => 'select',
		'options' => $types,
		'selected' => $filter->type ?? \App\Models\Filter::FILTER_TYPE_TEXT,
		'attributes' => [
			'id' => 'type',
			'name' => 'type',
			'required' => true
		]
	],
	[
		'label' => __('filters.default-value'),
		'tag' => 'textarea',
		'value' => $filter->value,
		'message' => __('filters.default-value-select'),
		'attributes' => [
			'id' => 'value',
			'name' => 'value',
			'maxlength' => 500,
			'rows' => 5
		]
	],
	[
		'label' => __('filters.min'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'min',
			'name' => 'min',
			'type' => 'number',
			'min' => 0,
			'value' => $filter->min
		]
	],
	[
		'label' => __('filters.max'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'max',
			'name' => 'max',
			'type' => 'number',
			'min' => 0,
			'value' => $filter->max
		]
	],
	[
		'label' => __('filters.step'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'step',
			'name' => 'step',
			'type' => 'number',
			'min' => 0,
			'step' => 0.01,
			'value' => $filter->step
		]
	],
	[
		'label' => __('filters.required'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'required',
			'name' => 'required',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $filter->required
		]
	],
	[
		'label' => __('filters.searchable'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'searchable',
			'name' => 'searchable',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $filter->searchable ?? true
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
			'checked' => $filter->active ?? true
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
			'value' => $filter->getTranslation('name', 'en'),
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true,
		]
	],
	[
		'label' => __('filters.display-label'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'display_label_en',
			'name' => 'display_label_en',
			'type' => 'text',
			'value' => $filter->getTranslation('display_label', 'en'),
			'maxlength' => 50,
		]
	],
	[
		'label' => __('filters.help-message'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'message_en',
			'name' => 'message_en',
			'type' => 'text',
			'value' => $filter->getTranslation('message', 'en'),
			'maxlength' => 100
		]
	],
	[
		'label' => __('filters.default-value'),
		'tag' => 'textarea',
		'value' => $filter->getTranslation('value', 'en'),
		'message' => __('filters.default-value-select'),
		'attributes' => [
			'id' => 'value_en',
			'name' => 'value_en',
			'maxlength' => 500,
			'rows' => 5
		]
	],
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.single_header', ['title' => __('filters.title-s'), 'icon' => 'fas fa-filter', 'actions' => $actions, 'updated_at' => $filter->updated_at])
    <form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form">
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
            </div>
            @csrf
        </div>
        @include('layouts.submit_button')
    </form>
@endsection
