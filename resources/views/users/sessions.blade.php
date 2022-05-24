<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'users.remove-sessions',
		'condition' => $sessions->isNotEmpty()
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('users.sessions') . ' > ' . $user->name, 'icon' => 'fa fa-user-lock', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th>{{ __('global.ip-address') }}</th>
					<th>{{ __('global.host') }}</th>
					<th>{{ __('global.user-agent') }}</th>
					<th>{{ __('global.last-activity') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($sessions as $row)
					<tr>
						<td>{{ $row->ip_address ?? '-' }}</td>
						<td>{{ $row->ip_address ? gethostbyaddr($row->ip_address) : '-' }}</td>
						<td>{{ $row->user_agent ?? '-' }}</td>
						<td data-order="{{ formatTimestamp($row->last_activity, 'U') }}">{{ formatLocalTimestamp($row->last_activity) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection