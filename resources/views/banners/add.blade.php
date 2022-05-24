<?php

use App\Models\Banner;

$form_action = route('banners.store');
$actions = null;

if ($banner->exists) {
	$form_action = route('banners.update', $banner->id);

	$actions = [
		[
			'type' => 'remove',
			'action' => ['banners.remove' => $banner->id]
		]
	];
}

$all_banner_types = [
	'' => '-',
	Banner::BANNER_TYPE_HOME => __('banners.banner-type-home'),
];

$fields_basic = [
	[
		'label' => __('banners.banner-type'),
		'tag' => 'select',
		'options' => $all_banner_types,
		'selected' => $banner->type,
		'attributes' => [
			'id' => 'type',
			'name' => 'type',
			'required' => true
		]
	],
	[
		'label' => __('forms.title'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'title',
			'name' => 'title',
			'type' => 'text',
			'value' => $banner->title,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true,
		]
	],
	[
		'label' => __('banners.subtitle'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'subtitle',
			'name' => 'subtitle',
			'type' => 'text',
			'value' => $banner->subtitle,
			'maxlength' => 50,
		]
	],
	[
		'label' => __('banners.order-column'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'order_column',
			'name' => 'order_column',
			'type' => 'number',
			'value' => $banner->order_column,
			'min' => 0,
			'max' => 255,
			'required' => true
		]
	],
	[
		'label' => __('forms.url'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'url',
			'name' => 'url',
			'type' => 'url',
			'value' => $banner->url,
			'maxlength' => 256,
			'required' => false,
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
			'checked' => $banner->active
		]
	],
];

$fields_media = [
	[
		'label' => __('banners.image'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'image',
			'name' => 'image',
			'type' => 'file',
			'accept' => 'image/*,video/*',
			'data-media-gallery-id' => 'image-gallery',
		],
	]
];

$fields_media_mobile = [
	[
		'label' => __('banners.image-mobile'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'image_mobile',
			'name' => 'image_mobile',
			'type' => 'file',
			'accept' => 'image/*,video/*',
			'data-media-gallery-id' => 'image_mobile-gallery',
		],
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.single_header', ['title' => __('banners.title-s'), 'icon' => 'fa fa-images', 'actions' => $actions, 'updated_at' => $banner->updated_at])
	<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form" enctype="multipart/form-data">
		<div class="card-body">
			@csrf
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
				@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>
				<div class="tab-pane fade" id="btabs-media" role="tabpanel">
					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('banners.image-info')])
					@include('layouts.forms.generate_gallery_fields', [
                        'data' => [
							'model' => $banner ?? null,
							'fields' => $fields_media,
							'collection' => 'image',
							'gallery_id' => 'image-gallery',
							'errors' => $errors
                        ]
                    ])

					@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'primary', 'text' => __('banners.image-mobile-info')])
					@include('layouts.forms.generate_gallery_fields', [
                        'data' => [
							'model' => $banner ?? null,
							'fields' => $fields_media_mobile,
							'collection' => 'image_mobile',
							'gallery_id' => 'image_mobile-gallery',
							'errors' => $errors
                        ]
                    ])

				</div>
			</div>
		</div>
		@include('layouts.submit_button')
	</form>
@endsection
