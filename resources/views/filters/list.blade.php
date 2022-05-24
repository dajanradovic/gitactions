<?php

$actions = [
	[
		'type' => 'activate',
		'action' => 'filters.activate'
	],
	[
		'type' => 'deactivate',
		'action' => 'filters.deactivate'
	],
	[
		'type' => 'remove',
		'action' => 'filters.remove-multi'
	],
];

$types = [
	\App\Models\Filter::FILTER_TYPE_TEXT => __('filters.type-text'),
	\App\Models\Filter::FILTER_TYPE_NUMBER => __('filters.type-number'),
	\App\Models\Filter::FILTER_TYPE_TEXTAREA => __('filters.type-textarea'),
	\App\Models\Filter::FILTER_TYPE_EMAIL => __('filters.type-email'),
	\App\Models\Filter::FILTER_TYPE_TEL => __('filters.type-tel'),
	\App\Models\Filter::FILTER_TYPE_URL => __('filters.type-url'),
	\App\Models\Filter::FILTER_TYPE_COLOR => __('filters.type-color'),
	\App\Models\Filter::FILTER_TYPE_RANGE => __('filters.type-range'),
	\App\Models\Filter::FILTER_TYPE_SELECT => __('filters.type-select'),
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.list_header', ['title' => __('filters.title-m'), 'icon' => 'fas fa-filter', 'path' => 'filters.add', 'actions' => $actions])
    <div class="card-body">
        <table width="100%" class="table table-head-custom js-datatable">
            <thead>
            <tr>
                <th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
                <th>{{ __('forms.name') }}</th>
                <th>{{ __('filters.display-label') }}</th>
                <th>{{ __('filters.type') }}</th>
                <th>{{ __('filters.searchable') }}</th>
                <th>{{ __('forms.active') }}</th>
                <th>{{ __('global.created-at') }}</th>
                @include('layouts.options_column_header')
            </tr>
            </thead>
            <tbody>
            @foreach($filters as $row)
                <tr>
                    <td>@include('layouts.edit_button', ['path' => ['filters.edit' => $row->id]])</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->display_label ?? $row->name }}</td>
                    <td>{{ $types[$row->type] ?? '-' }}</td>
                    <td>@include('layouts.bool_badge', ['value' => $row->searchable])</td>
                    <td>@include('layouts.bool_badge', ['value' => $row->active])</td>
                    <td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
                    <td>@include('layouts.option_buttons', ['value' => $row->id])</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection