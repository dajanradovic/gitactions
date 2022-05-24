<?php

$actions = [
	[
		'type' => 'test-api',
		'action' => ['api.blogs.list' => []]
	],
	[
		'type' => 'remove',
		'action' => 'blogs.remove-multi',
		'condition' => $blogs->isNotEmpty()
	]
];

$search = [
	'value' => $search ?? null,
	'route' => 'blogs.search'
];

$token = auth()->user()->token();

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
	@include('layouts.list_header', ['title' => __('blogs.title-m'), 'icon' => 'fa fa-edit', 'path' => 'blogs.add', 'actions' => $actions, 'search' => $search])
	<div class="card-body">
		<form class="form">
			<div class="row">
				<div class="col-sm-6">
					@include('layouts.forms.generate_form_fields', ['fields' => $fields_daterange])
				</div>
			</div>
		</form>

		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.title') }}</th>
					<th>{{ __('activities.activity') }}</th>
					<th>{{ __('forms.published-at') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($blogs as $row)
					<?php

					$activity_modal = json_encode([
						'api' => route('api.blogs.activities', $row->id),
						'token' => $token,
						'title' => $row->title . ' > ' . __('activities.activity')
					]);

					?>
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['blogs.edit' => $row->id]])</td>
						<td>{{ $row->title }}</td>
						<td>
							<a href="#" title="{{ __('activities.activity') }}" data-modal-data="{{ $activity_modal }}" data-toggle="modal" data-target="#activity-modal" class="text-hover-primary">
								<i class="fa fa-clock"></i> {{ $row->activities_count }}
							</a>
						</td>
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
