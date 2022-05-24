<?php

$roles ??= \App\Models\Role::where('protected', 'like', $protected ?? '%')->orderBy('name')->get();

$all_roles = ['' => __('global.none')];

foreach ($roles as $row) {
	$all_roles[$row->id] = $row->name;
}

$fields_roles = [
	[
		'label' => __('roles.title-s'),
		'tag' => 'select',
		'options' => $all_roles,
		'attributes' => [
			'id' => 'role_id',
			'name' => 'role_id'
		]
	]
];

?>

<div class="modal fade" id="roles-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('roles.title-m') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}" title="{{ __('global.close') }}">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>

			<div class="modal-body">
				<form class="form form-notify" method="post" action="{{ route('roles.store-users') }}" autocomplete="off" id="roles-form">
					@csrf
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_roles])
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					<i class="la la-close"></i> {{ __('global.close') }}
				</button>

				<button type="submit" class="btn btn-success" form="roles-form">
					<i class="la la-save"></i> {{ __('forms.save') }}
				</button>
			</div>
		</div>
	</div>
</div>