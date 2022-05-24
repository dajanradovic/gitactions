<?php

$actions = [
	[
		'type' => 'truncate',
		'action' => 'db.truncate-multi'
	],
	[
		'type' => 'remove',
		'action' => 'db.remove-multi'
	]
];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('db.title'), 'icon' => 'fa fa-database', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th>{{ __('forms.name') }}</th>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export>{{ __('db.columns') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($tables as $table)
					<tr>
						<td><a href="{{ route('db.show', $table->getName()) }}" title="{{ __('db.columns') }}" data-container="body" data-toggle="tooltip" data-placement="left">{{ $table->getName() }}</a></td>
						<td>
							<button type="button" title="{{ $table->getName() }}" class="btn btn-sm btn-outline-primary btn-elevate btn-circle btn-icon" data-container="body" data-toggle="popover" data-trigger="focus" data-html="true" data-placement="top" data-content="@include('layouts.db_columns_popover', compact('table'))">
								<i class="fa fa-info"></i>
							</button>
						</td>
						<td>@include('layouts.option_buttons', ['value' => $table->getName()])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection