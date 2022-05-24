<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'roles.remove-multi',
		'condition' => $roles->isNotEmpty()
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('roles.title-m'), 'icon' => 'fa fa-ban', 'path' => 'roles.add', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.name') }}</th>
					<th>{{ __('roles.protected') }}</th>
					<th>{{ __('users.title-m') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($roles as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['roles.edit' => $row->id]])</td>
						<td>{{ $row->name }}</td>
						<td>@include('layouts.bool_badge', ['value' => $row->protected])</td>
						<td>{{ $row->users_count }}</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection