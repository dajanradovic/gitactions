<?php

$actions = [
	[
		'type' => 'roles-modal',
		'action' => 'roles.store-users',
		'condition' => $users->isNotEmpty()
	],
	[
		'type' => 'activate',
		'action' => 'users.activate',
		'condition' => $users->isNotEmpty()
	],
	[
		'type' => 'deactivate',
		'action' => 'users.deactivate',
		'condition' => $users->isNotEmpty()
	],
	[
		'type' => 'remove',
		'action' => 'customers.remove-multi',
		'condition' => $users->isNotEmpty()
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('customers.title-m'), 'icon' => 'fa fa-users', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.name') }}</th>
					<th>{{ __('customers.surname') }}</th>
					<th>{{ __('forms.email') }}</th>
					<th>{{ __('roles.title-s') }}</th>
					<th>{{ __('customers.delivery-address') }}</th>
					<th>{{ __('customers.invoice-address') }}</th>
					<th>{{ __('forms.timezone') }}</th>
					<th>{{ __('users.verified') }}</th>
					<th>{{ __('forms.active') }}</th>
					<th>{{ __('customers.newsletter') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($users as $row)
					<?php $authParent = $row->authParent; ?>
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['customers.edit' => $row->id]])</td>
						<td>{{ $authParent->name }}</td>
						<td>{{ $row->surname }}</td>
						<td><a href="mailto:{{ $authParent->email }}">{{ $authParent->email }}</a></td>
						<td><a href="{{ $authParent->role_id ? route('roles.edit', $authParent->role_id) : '#' }}">{{ $authParent->role_id ? $authParent->role->name : '-' }}</a></td>
						<td>{{ $row->getFullAddressByType(App\Models\Address::DELIVERY_ADDRESS) }}</td>
						<td>{{ $row->getFullAddressByType(App\Models\Address::INVOICE_ADDRESS) }}</td>
						<td>{{ $authParent->timezone }}</td>
						<td>@include('layouts.bool_badge', ['value' => $authParent->hasVerifiedEmail()])</td>
						<td>@include('layouts.bool_badge', ['value' => $authParent->active])</td>
						<td>@include('layouts.bool_badge', ['value' => $row->newsletter])</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@include('layouts.modals.roles')
@endsection
