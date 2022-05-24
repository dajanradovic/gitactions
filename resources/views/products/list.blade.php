<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'products.activate'
	],
	[
		'type' => 'deactivate',
		'action' => 'products.deactivate'
	],
	[
		'type' => 'remove',
		'action' => 'products.remove-multi'
	],
];

$search = [
	'value' => $search ?? null,
	'route' => 'products.search'
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('products.title-m'), 'icon' => 'fa fa-edit', 'path' => 'products.add', 'actions' => $actions, 'search' => $search])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.title') }}</th>
					<th>{{ __('forms.published-at') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($products as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['products.edit' => $row->id]])</td>
						<td>{{ $row->name }}</td>
						<td data-order="{{ formatTimestamp($row->published_at, 'U') }}">{{ formatLocalTimestamp($row->published_at) }}</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@include('layouts.modals.activity')
@endsection
