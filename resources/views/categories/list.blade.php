<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'categories.activate'
	],
	[
		'type' => 'deactivate',
		'action' => 'categories.deactivate'
	],
	[
		'type' => 'remove',
		'action' => 'categories.remove-multi'
	],
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.list_header', ['title' => __('categories.title-m'), 'icon' => 'fas fa-boxes', 'path' => 'categories.add', 'actions' => $actions])
    <div class="card-body">
        <table width="100%" class="table table-head-custom js-datatable">
            <thead>
            <tr>
                <th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
                <th>{{ __('forms.name') }}</th>
                <th>{{ __('forms.active') }}</th>
                {{-- <th>{{ __('categories.parent') }}</th>
                <th>{{ __('categories.root') }}</th> --}}
                <th>{{ __('global.created-at') }}</th>
                @include('layouts.options_column_header')
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $row)
                <tr>
                    <td>@include('layouts.edit_button', ['path' => ['categories.edit' => $row->id]])</td>
                    <td>{{ $row->name }}</td>
                    <td>@include('layouts.bool_badge', ['value' => $row->active])</td>
                    {{-- <td>{{ $row->parent->name ?? '-' }}</td>
                    <td>{{ $row->rootAncestor->name ?? '-' }}</td> --}}
                    <td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
                    <td>@include('layouts.option_buttons', ['value' => $row->id])</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
