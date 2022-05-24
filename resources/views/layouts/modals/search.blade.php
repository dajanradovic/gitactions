<?php

$fields_search = array_merge($extra_fields ?? [], [
	[
		'tag' => 'input',
		'attributes' => [
			'id' => 'global-search',
			'name' => 'search',
			'type' => 'search',
			'placeholder' => __('global.search'),
			'value' => $value ?? null,
			'required' => true
		]
	]
]);

?>

<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('global.search') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body">
				<form class="form form-notify" action="{{ $route ?? '' }}" autocomplete="off" id="search-form">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_search])
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="la la-close"></i> {{ __('global.close') }}
				</button>

				<button type="submit" class="btn btn-success" form="search-form">
					<i class="la la-search"></i> {{ __('global.search') }}
				</button>
			</div>
		</div>
	</div>
</div>