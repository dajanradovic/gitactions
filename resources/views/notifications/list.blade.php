<?php

$actions = [
	[
		'type' => 'cancel',
		'action' => 'notifications.cancel-multi',
		'condition' => $notifications->isNotEmpty()
	],
	[
		'type' => 'remove',
		'action' => 'notifications.remove-multi',
		'condition' => $notifications->isNotEmpty()
	]
];

$fields_daterange = !isset($start_date) || !isset($end_date) ? [] : [
	[
		'label' => __('forms.daterange'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'daterangepicker',
			'type' => 'text',
			'value' => formatLocalTimestamp($start_date, 'd/m/Y H:i') . ' - ' . formatLocalTimestamp($end_date, 'd/m/Y H:i'),
			'class' => 'js-search-datetimerangepicker',
			'readonly' => true
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'daterangepicker-first',
			'name' => 'start_date',
			'type' => 'hidden',
			'value' => $start_date
		]
	],
	[
		'tag' => 'hidden',
		'attributes' => [
			'id' => 'daterangepicker-second',
			'name' => 'end_date',
			'type' => 'hidden',
			'value' => $end_date
		]
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('notifications.title-m'), 'icon' => 'fa fa-broadcast-tower', 'path' => 'notifications.add', 'actions' => $actions])
	<div class="card-body">
		<form class="form">
			<div class="form__heading">
				<div class="row">
					<div class="col-sm-6">
						@include('layouts.forms.generate_form_fields', ['fields' => $fields_daterange])
					</div>
				</div>
			</div>
		</form>

		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th>{{ __('forms.title') }}</th>
					<th>{{ __('forms.url') }}</th>
					<th>{{ __('notifications.scheduled-at') }}</th>
					<th>{{ __('notifications.targets') }}</th>
					<th>{{ __('notifications.canceled') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($notifications as $row)
					<tr>
						<td><a href="{{ route('notifications.show', $row->id) }}" title="{{ __('global.view') }}" data-container="body" data-toggle="tooltip" data-placement="left">{{ $row->title }}</a></td>
						<td>@include('layouts.bool_badge', ['value' => $row->url])</td>
						<td data-order="{{ formatTimestamp($row->scheduled_at, 'U') }}">{{ formatLocalTimestamp($row->scheduled_at) }}</td>
						<td>{{ $row->targets_count }}</td>
						<td>@include('layouts.bool_badge', ['value' => $row->canceled, 'inverted' => true])</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
