<?php

$fields_feedback = [
	[
		'tag' => 'textarea',
		'attributes' => [
			'id' => 'feedback-message',
			'name' => 'message',
			'rows' => 10,
			'placeholder' => __('forms.message'),
			'required' => true
		]
	]
];

?>

<div class="modal fade" id="feedback-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('global.feedback') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-notify" method="post" action="{{ route('feedback') }}" autocomplete="off" id="feedback-form">
					@csrf
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_feedback])
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="la la-close"></i> {{ __('global.close') }}
				</button>

				<button type="submit" class="btn btn-success" form="feedback-form">
					<i class="la la-envelope"></i> {{ __('global.send') }}
				</button>
			</div>
		</div>
	</div>
</div>