<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'discounts.activate'
	],
	[
		'type' => 'deactivate',
		'action' => 'discounts.deactivate'
	],
	[
		'type' => 'remove',
		'action' => 'discounts.remove-multi'
	],
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('discounts.title-m'), 'icon' => 'fas fa-percent', 'path' => 'discounts.add', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('discounts.title-s') }}</th>
					<th>{{ __('discounts.code') }}</th>
					<th>{{ __('discounts.amount') }}</th>
					<!-- <th>{{ __('discounts.max-use-list') }}</th> -->
					<th>{{ __('discounts.period-from') }}</th>
					<th>{{ __('discounts.period-to') }}</th>
					<!-- <th>{{ __('discounts.is-percentage') }}</th> -->
					<th>{{ __('forms.active') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($discounts as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => [$row->getEditViewPath() => $row->id]])</td>
						<td>{{ $row->title }}</td>
						<td>{{ $row->code }}</td>
						<td>{{ $row->amount . ($row->is_percentage ? '%' : (' ' . setting('currency_code'))) }}</td>
						<!-- <td>{{ $row->max_use }}</td> -->
						<td data-order="{{ $row->period_from ? formatTimestamp($row->period_from, 'U') : 0 }}">{{ $row->period_from ? formatLocalTimestamp($row->period_from) : '-'}}</td>
						<td data-order="{{ $row->period_to ? formatTimestamp($row->period_to, 'U') : 0 }}">{{ $row->period_to ? formatLocalTimestamp($row->period_to) : '-'}}</td>
						<!-- <td>@include('layouts.bool_badge', ['value' => $row->is_percentage])</td> -->
						<td>@include('layouts.bool_badge', ['value' => $row->active])</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
