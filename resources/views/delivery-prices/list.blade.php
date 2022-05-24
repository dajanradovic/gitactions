<?php

$actions = [
	[
		'type' => 'remove',
		'action' => 'delivery-prices.remove-multi'
	],
];

?>

@extends('layouts.master')

@section('content')
    @include('layouts.list_header', ['title' => __('delivery-prices.title-m'), 'icon' => 'fas fa-boxes', 'actions' => $actions])
    <div class="card-body">
        <table width="100%" class="table table-head-custom js-datatable">
            <thead>
            <tr>
                <th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
                <th>{{ __('delivery-prices.delivery-service') }}</th>
                <th>{{ __('delivery-prices.country') }}</th>
				<th>{{ __('delivery-prices.up_to_2') }}</th>
				<th>{{ __('delivery-prices.up_to_5') }}</th>
				<th>{{ __('delivery-prices.up_to_10') }}</th>
				<th>{{ __('delivery-prices.up_to_15') }}</th>
				<th>{{ __('delivery-prices.up_to_20') }}</th>
				<th>{{ __('delivery-prices.up_to_25') }}</th>
				<th>{{ __('delivery-prices.up_to_32') }}</th>
                @include('layouts.options_column_header')
            </tr>
            </thead>
            <tbody>
            @foreach($delivery_prices as $row)
                <tr>
                    <td>@include('layouts.edit_button', ['path' => ['delivery-prices.edit' => $row->id]])</td>
                    <td>{{ $row->delivery_service }}</td>
                    <td>{{ $row::getCountryFullName($row->country_code)}}</td>
                    <td>{{ $row->up_to_2_kg }}</td>
                    <td>{{ $row->up_to_5_kg }}</td>
					<td>{{ $row->up_to_10_kg }}</td>
					<td>{{ $row->up_to_15_kg }}</td>
					<td>{{ $row->up_to_20_kg }}</td>
					<td>{{ $row->up_to_25_kg }}</td>
					<td>{{ $row->up_to_32_kg }}</td>
                    <td>@include('layouts.option_buttons', ['value' => $row->id])</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
