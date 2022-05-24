@extends('layouts.master')

@section('content')
	@include('layouts.list_header', ['title' => __('orders.title-m'), 'icon' => 'fa fa-edit'])
	<div class="card-body">
		<table width="100%" class="table table-head-custom js-datatable">
			<thead>
				<tr>
					<th data-datatable-searchable="0" data-datatable-orderable="0" data-datatable-exclude-export></th>
					<th>{{ __('orders.order-number') }}</th>
					<th>{{ __('orders.price-before-discounts') }}</th>
					<th>{{ __('orders.discounts') }}</th>
					<th>{{ __('orders.tax') }}</th>
					<th>{{ __('orders.shipping') }}</th>
					<th>{{ __('orders.final-price') }}</th>
					<th>{{ __('orders.currency') }}</th>
					<th>{{ __('orders.payment-method') }}</th>
					<th>{{ __('orders.delivery-method') }}</th>
					<th>{{ __('orders.status') }}</th>
					<th>{{ __('global.created-at') }}</th>
					@include('layouts.options_column_header')
				</tr>
			</thead>
			<tbody>
				@foreach($orders as $row)
					<tr>
						<td>@include('layouts.edit_button', ['path' => ['orders.edit' => $row->id]])</td>
						<td>{{ $row->order_number }}</td>
						<td>{{ $row->total_price }}</td>
						<td>{{ $row->total_discounts }}</td>
						<td>{{ $row->tax_total }}</td>
						<td>{{ $row->shipping_price }}</td>
						<td>{{ $row->final_price }}</td>
						<td>{{ $row->currency }}</td>
						<td>{{ $row->getPaymentMethodName()}}</td>
						<td>{{ $row->getDeliveryMethodName()}}</td>
						<td>{{ $row->getStatusName()}}</td>
						<td data-order="{{ formatTimestamp($row->created_at, 'U') }}">{{ formatLocalTimestamp($row->created_at) }}</td>
						<td>@include('layouts.option_buttons', ['value' => $row->id])</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@include('layouts.modals.activity')
@endsection
