<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'banners.remove-multi'
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('banners.title-m'), 'icon' => 'fa fa-images', 'path' => 'banners.add', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('banners.banner-type') }}</th>
					<th>{{ __('forms.title') }}</th>
					<th>{{ __('banners.order-column') }}</th>
					<th>{{ __('forms.url') }}</th>
					<th>{{ __('forms.active') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($banners as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['banners.edit' => $row->id]])</td>
						<td>{{ $row->type == 1 ? __('banners.banner-type-home') : __('banners.banner-type-gift-finder') }}</td>
						<td>{{ $row->title }}</td>
						<td>{{ $row->order_column }}</td>
						<td>
							@if ($row->url) <a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a>
							@else - @endif
						</td>
						<td>@include('layouts.bool_badge', ['value' => $row->active])</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection
