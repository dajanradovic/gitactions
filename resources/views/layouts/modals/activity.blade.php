<?php

$fields_search = [
	[
		'tag' => 'input',
		'attributes' => [
			'type' => 'search',
			'placeholder' => __('global.search'),
			'class' => 'modal-search-input',
			'maxlength' => 50,
			'autocomplete' => 'off'
		]
	]
];

?>

<!--begin::Modal-->
<div class="modal fade" id="activity-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('activities.activity') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>

			<div class="modal-body">
				<div class="progress mb-5">
					<div class="progress-bar bg-primary" role="progressbar" style="width: 0%;"></div>
				</div>

				@include('layouts.forms.generate_form_fields', ['fields' => $fields_search])

				<div class="timeline timeline-3">
					<div class="timeline-items modal-list-container" data-scroll="true" data-height="400"></div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary modal-load-more">
					<i class="la la-refresh"></i> {{ __('global.load-more') }}
				</button>

				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="la la-close"></i> {{ __('global.close') }}
				</button>
			</div>
		</div>
	</div>

	<template class="modal-list-template">
		<div class="timeline-item">
			<div class="timeline-media">
				<img class="modal-user-avatar" loading="lazy" decoding="async" hidden>
				<span class="symbol symbol-circle symbol-light-primary modal-user-letter-wrapper" hidden>
					<span class="symbol-label text-primary font-size-h3 font-weight-bold modal-user-letter"></span>
				</span>
			</div>
			<div class="timeline-content">
				<div class="d-flex align-items-center justify-content-between mb-3">
					<div class="mr-2">
						<a class="text-dark-75 text-hover-primary font-weight-bold modal-user-link"></a>
						<span class="text-muted ml-2 mr-5 modal-time"></span>

						<span class="label label-success label-inline label-rounded modal-activity-badge-{{ \App\Models\UserActivity::TYPE_CREATE }}" hidden>{{ __('activities.created') }}</span>
						<span class="label label-info label-inline label-rounded modal-activity-badge-{{ \App\Models\UserActivity::TYPE_READ }}" hidden>{{ __('activities.read') }}</span>
						<span class="label label-warning label-inline label-rounded modal-activity-badge-{{ \App\Models\UserActivity::TYPE_UPDATE }}" hidden>{{ __('activities.updated') }}</span>
						<span class="label label-danger label-inline label-rounded modal-activity-badge-{{ \App\Models\UserActivity::TYPE_DELETE }}" hidden>{{ __('activities.deleted') }}</span>
					</div>
				</div>
				<p class="p-0">{{ __('storage.model') }}: <a class="item-type"></a></p>
				<p class="p-0 modal-text" hidden></p>
				<p class="p-0 text-muted">{{ __('global.ip-address') }}: <span class="ip-address"></span></p>
			</div>
		</div>
	</template>
</div>
<!--end::Modal-->
