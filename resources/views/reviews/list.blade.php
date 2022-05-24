<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'reviews.activate'
	],
	[
		'type' => 'deactivate',
		'action' => 'reviews.deactivate'
	],
	[
		'type' => 'remove',
		'action' => 'reviews.remove-multi'
	],
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('reviews.title-m'), 'icon' => 'fas fa-percent', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th>{{ __('reviews.type') }}</th>
					<th>{{ __('reviews.rating') }}</th>
					<th>{{ __('reviews.description') }}</th>
					<th>{{ __('forms.active') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($reviews as $row)
					<tr>
						<td>{{ $row->determineType() }}</td>
						<td>{{ $row->rating }}</td>
						<td>{{ $row->description }}</td>
						<td>@include('layouts.bool_badge', ['value' => $row->active])</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
