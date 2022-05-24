<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'cheese-club.remove-multi',
		'condition' => $cheese_clubs->isNotEmpty()
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('cheese-club.title-m'), 'icon' => 'fas fa-cheese',  'path' => 'cheese-club.add', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('forms.name') }}</th>
					<th>{{ __('customers.surname') }}</th>
					<th>{{ __('forms.email') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($cheese_clubs as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['cheese-club.edit' => $row->id]])</td>
						<td>{{ $row->name }}</td>
						<td>{{ $row->surname }}</td>
						<td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@include('layouts.modals.roles')
@endsection
