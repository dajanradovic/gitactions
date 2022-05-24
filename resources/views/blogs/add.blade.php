<?php

$token = auth()->user()->token();
$form_action = route('blogs.store');
$actions = null;

$tags = $blog->tags()->with('tag')->get()->map(function ($item) {
	return $item->tag->name;
})->implode($blog->getTagDelimiter());

if ($blog->exists) {
	$form_action = route('blogs.update', $blog->id);

	$activity_modal = json_encode([
		'api' => route('api.blogs.activities', $blog->id),
		'token' => $token,
		'title' => $blog->title . ' > ' . __('activities.activity')
	]);

	$actions = [
		[
			'type' => 'test-api',
			'action' => ['api.blogs.single' => $blog->id]
		],
		[
			'type' => 'activity-modal',
			'action' => $activity_modal
		],
		[
			'type' => 'remove',
			'action' => ['blogs.remove' => $blog->id]
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
			'value' => $blog->title,
			'maxlength' => 50,
			'required' => true,
			'autofocus' => true,
			'data-slug-target-id' => 'blog-slug'
		]
	],
	[
		'label' => __('forms.slug'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'blog-slug',
			'name' => 'slug',
			'type' => 'text',
			'value' => $blog->slug,
			'maxlength' => 50,
			'required' => true,
			'data-slug-target-event' => 'onblur',
			'data-slug-target-id' => 'blog-slug'
		]
	],
	[
		'label' => __('forms.published-at'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'published_at',
			'name' => 'published_at',
			'type' => 'text',
			'value' => $blog->published_at ? formatLocalTimestamp($blog->published_at, 'Y-m-d H:i') : null,
			'class' => 'js-datetimepicker datetimepicker-input',
			'data-toggle' => 'datetimepicker',
			'data-target' => '#published_at',
			'placeholder' => __('forms.now'),
			'readonly' => true
		]
	],
	[
		'label' => __('forms.tags'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'tags',
			'name' => 'tags',
			'type' => 'text',
			'class' => 'tagify',
			'value' => $tags,
			'data-api' => route('api.tags.list'),
			'data-token' => $token
		]
	],
	[
		'label' => __('forms.body'),
		'tag' => 'textarea',
		'value' => $blog->body,
		'attributes' => [
			'id' => 'body',
			'name' => 'body',
			'maxlength' => 5000,
			'rows' => 10,
			'required' => true
		]
	]
];

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
	@include('layouts.single_header', ['title' => __('blogs.title-s'), 'icon' => 'fa fa-edit', 'actions' => $actions, 'updated_at' => $blog->updated_at])
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
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])
				</div>
				<div class="tab-pane fade" id="btabs-media" role="tabpanel">
					@include('layouts.forms.generate_gallery_fields', [
						'data' => [
							'model' => $blog,
							'fields' => $fields_media,
							'collection' => 'image',
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
