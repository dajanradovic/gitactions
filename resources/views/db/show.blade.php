<?php

$actions = [
	[
		'type' => 'remove',
		'action' => ['db.remove-columns-multi' => $table->getName()],
		'condition' => !empty($table->getColumns())
	]
];

$primary = $table->getPrimaryKey();
$primary = $primary ? $primary->getColumns() : [];

?>

@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => $table->getName() . ' > ' . __('db.columns'), 'icon' => 'fa fa-database', 'actions' => $actions])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th>{{ __('forms.name') }}</th>
					<th>{{ __('db.column-type') }}</th>
					<th>{{ __('db.is-primary') }}</th>
					<th>{{ __('db.is-null') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($table->getColumns() as $column)
					<tr>
						<td>{{ $column->getName() }}</td>
						<td>{{ $column->getType()->getName() }}</td>
						<td>@include('layouts.bool_badge', ['value' => in_array($column->getName(), $primary)])</td>
						<td>@include('layouts.bool_badge', ['value' => !$column->getNotnull()])</td>
						<td>@include('layouts.option_buttons', ['value' => $column->getName()])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection