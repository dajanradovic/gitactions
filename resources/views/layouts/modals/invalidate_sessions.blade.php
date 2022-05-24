<?php

$fields_invalidate_sessions = [
	[
		'tag' => 'input',
		'attributes' => [
			'id' => 'current-password',
			'name' => 'current_password',
			'type' => 'password',
			'placeholder' => __('forms.password') . ' (' . __('forms.minimum-chars', ['n' => setting('min_pass_len')]) . ')',
			'minlength' => setting('min_pass_len'),
			'required' => true
		]
	]
];

?>

<div class="modal fade" id="invalidate-sessions-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('global.invalidate-sessions') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-notify" method="post" action="{{ route('invalidate-sessions') }}" autocomplete="off" id="invalidate-sessions-form">
					@csrf
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_invalidate_sessions])
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="la la-close"></i> {{ __('global.close') }}
				</button>

				<button type="submit" class="btn btn-success" form="invalidate-sessions-form">
					<i class="la la-check"></i> {{ __('global.invalidate') }}
				</button>
			</div>
		</div>
	</div>
</div>