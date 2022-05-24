<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'monitors.activate',
		'condition' => $monitors->isNotEmpty()
	],
	[
		'type' => 'deactivate',
		'action' => 'monitors.deactivate',
		'condition' => $monitors->isNotEmpty()
	],
	[
		'type' => 'remove',
		'action' => 'monitors.remove-multi',
		'condition' => $monitors->isNotEmpty()
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('monitors.title-m'), 'icon' => 'fa fa-clock', 'path' => 'monitors.add', 'actions' => $actions])
	<div class="card-body">

		@if(!setting('monitor_active'))
			@include('layouts.alert', ['icon' => 'fa fa-info', 'state' => 'warning', 'text' => __('monitors.global-disabled', ['route' => route('settings.general.edit', ['tab' => 'btabs-monitor'])])])
		@endif

		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.url') }}</th>
					<th>{{ __('monitors.interval') }} ({{ __('settings.minutes') }})</th>
					<th>{{ __('monitors.method') }}</th>
					<th>{{ __('monitors.cert-check') }}</th>
					<th>{{ __('forms.active') }}</th>
					<th>{{ __('monitors.last-check') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($monitors as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['monitors.edit' => $row->id]])</td>
						<td><a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a></td>
						<td>{{ $row->uptime_check_interval_in_minutes }}</td>
						<td>@include('layouts.method_badge', ['methods' => $row->uptime_check_method])</td>
						<td>@include('layouts.bool_badge', ['value' => $row->certificate_check_enabled])</td>
						<td>@include('layouts.bool_badge', ['value' => $row->uptime_check_enabled])</td>
						<td data-order="{{ formatTimestamp($row->uptime_last_check_date, 'U') }}">{{ formatLocalTimestamp($row->uptime_last_check_date) }}</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection