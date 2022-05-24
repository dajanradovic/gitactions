<?php

$form_action = route('orders.update', $order->id);

$currency = $order->currency;

$statuses = [
	'' => '-',
	\App\Models\Order::STATUS_CANCELED => __('orders.canceled'),
	\App\Models\Order::STATUS_PENDING => __('orders.pending'),
	\App\Models\Order::STATUS_PAID => __('orders.paid'),
	\App\Models\Order::STATUS_OFFER_SENT => __('orders.offer-sent'),
	\App\Models\Order::STATUS_REFUNDED => __('orders.refunded')
];

foreach ($order->shipments as $index => $shipment) {
	$fields_shipments[] = [
		'label' => 'Shipment ' . $index + 1,
		'tag' => 'input',
		'attributes' => [
			'id' => 'shipments',
			'name' => "shipments[{$shipment->id}]",
			'type' => 'text',
			'value' => $shipment->shipment_number,
			'min' => 0,
			'max' => 30
		]
	];
}

$fields_ready_for_pickup = [
	[

		'label' => __('orders.ready-for-pickup'),
		'tag' => 'checkbox',
		'attributes' => [
			'id' => 'ready_for_pickup',
			'name' => 'ready_for_pickup',
			'value' => 1,
			'type' => 'checkbox',
			'checked' => $order->ready_for_pickup
		]
	]
];

$fields_basic = [
	[
		'label' => __('orders.status'),
		'tag' => 'select',
		'options' => $statuses,
		'selected' => $order->status,
		'attributes' => [
			'id' => 'status',
			'name' => 'status',
		]
	],
	[
		'label' => __('orders.payment-id'),
		'tag' => 'input',
		'attributes' => [
			'id' => 'payment_id',
			'name' => 'payment_id',
			'type' => 'text',
			'value' => $order->payment_id,
			'min' => 0,
			'max' => 50
		]
	]
];

?>

@extends('layouts.master')

@section('content')

	@include('layouts.list_header', ['title' => __('orders.title-s'), 'icon' => 'fa fa-calendar-alt', 'updated_at' => $order->updated_at])
	<div class="card-body">
		<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-primary" role="tablist">
					<li class="nav-item">
						<a href="#btabs-basic" class="nav-link active" data-toggle="tab">
							<span class="nav-icon"><i class="fa fa-info"></i></span>
							<span class="nav-text">{{ __('settings.menu-basic') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#btabs-order-items" class="nav-link" data-toggle="tab">
							<span class="nav-icon"><i class="fa fa-info"></i></span>
							<span class="nav-text">{{ __('orders.order-items') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#btabs-shipping" class="nav-link" data-toggle="tab">
							<span class="nav-icon"><i class="fas fa-shipping-fast"></i></span>
							<span class="nav-text">{{ __('orders.shipping-info') }}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#btabs-media" class="nav-link" data-toggle="tab">
							<span class="nav-icon"><i class="fa fa-image"></i></span>
							<span class="nav-text">{{ __('settings.menu-media') }}</span>
						</a>
					</li>
		</ul>
		<div class="tab-content mt-5">
			<div class="tab-pane fade show active" id="btabs-basic" role="tabpanel">
					<table class="table">

						<tbody>
							<tr>
								<th scope="row">Created at</th>
								<td>{{ formatLocalTimestamp($order->created_at) }}</td>
							</tr>
							<tr>
								<th scope="row">Customer</th>
								<td>	@if(!$order->guest_mode)

											@if($order->customer_id)
												<a href="{{ route('customers.edit', $order->customer_id) }}">{{ $customer->authParent->name . ' ' . $customer->surname }}</a>
											@else
												{{$order->order_details['customer_name']}}
											@endif
										@else
											{{$order->delivery_address['name']}}
										@endif
								</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.customer-email' )}}</th>
								<td>{{ $order->customer_email }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.price-before-discounts' )}}</th>
								<td>{{ $order->total_price }} {{ $currency }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.discounts') }}</th>
								<td>{{ $order->total_discounts }} {{ $currency }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.price-after-discounts') }}</th>
								<td>{{ $order->total_price_minus_discounts }} {{ $currency }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.tax') }}</th>
								<td>{{ $order->tax_total }} {{ $currency }}</td>
							</tr>
							<tr class="table-active">
								<th scope="row">{{__('orders.final-price') }}</th>
								<td>{{ $order->final_price }} {{ $currency }}</td>
							</tr>
							<tr>
							@if(!$order->store_id)

								<th scope="row">{{ __('orders.shipping') }}</th>
								<td>

											{{ $order->shipping_price }} {{ $currency }}
								</td>


							@else
								<th scope="row">{{ __('orders.pickup-store') }} </th>
								<td>
										{{ $order->store_id }}
								</td>

							@endif
							</tr>
							<tr>
								<th scope="row">{{ __('orders.status') }}</th>
								<td>
									@include('layouts.orders.status_badge', ['status' => $order->status])
								</td>
							</tr>
							<tr>
								<th scope="row">{{ __('orders.payment-method') }}</th>
								<td>
								{{ $order->getPaymentMethodName()}}
								</td>
							</tr>

							@if($order->payment_type == $order::PAYMENT_METHOD_CARD)
							<tr>
								<th scope="row">{{ __('orders.payment-provider') }}</th>
								<td>
											{{ $order->getCardPaymentProviderName() }}
								</td>
							</tr>
							@endif
							<tr>
								<th scope="row">{{ __('orders.payment-id') }}</th>
								<td>
								{{ $order->payment_id ?? '-'}}
								</td>
							</tr>
								<tr>
								<th scope="row">{{ __('orders.payment-created-at') }}</th>
								<td>
								{{ $order->payment_created_at ? formatLocalTimestamp($order->payment_created_at) : '-' }}
								</td>
							</tr>
							@if($order->storage()->getFirstFile('invoice'))
							<tr>
								<th scope="row">{{ __('orders.invoice') }}</th>
								<td>
											<a href="{{$order->storage()->getFirstFile('invoice')->getUrl()}}" target="_blank">		 View invoice		</a>
								</td>
							</tr>
							@elseif($order->storage()->getFirstFile('offer'))
							<tr>
								<th scope="row">{{ __('orders.offer') }}</th>
								<td>
											<a href="{{$order->storage()->getFirstFile('offer')->getUrl()}}" target="_blank">		 View offer		</a>
								</td>
							</tr>
							@endif

						</tbody>
					</table>
					<form class="form form-notify" action="{{ $form_action }}" method="post" autocomplete="off" id="main-form">
							@if($order->store_id)
								@include('layouts.forms.generate_form_fields', ['fields' => $fields_ready_for_pickup])

							@else
								@include('layouts.forms.generate_form_fields', ['fields' => $fields_shipments])
							@endif
							@include('layouts.forms.generate_form_fields', ['fields' => $fields_basic])

							@csrf
							@include('layouts.submit_button')
					</form>

			</div>
			<div class="tab-pane fade" id="btabs-order-items" role="tabpanel">

			<table class="table">
					<thead>
						<tr>
						<th scope="col">#</th>
						<th scope="col">{{ __('products.name') }}</th>
						<th scope="col">{{ __('products.price') }}</th>
						<th scope="col">{{ __('orders.discounts') }}</th>
						<th scope="col">{{ __('orders.price-after-discounts') }}</th>
						<th scope="col">{{ __('orders.tax') }}</th>
						<th scope="col">{{ __('orders.tax-rate') }}</th>
						<th scope="col">{{ __('products.quantity') }}</th>
						<th scope="col">{{ __('orders.final-price') }}</th>
						</tr>
					</thead>
					<tbody>
						@foreach($orderItems as $index=>$item)
						<tr>
							<th scope="row">{{$index + 1}}</th>
							<td>
								@if($item->product_id)

									<a href="{{ route('products.edit', $item->product_id) }}">{{ $item->product->name }}</a>
								@else
									{{$item->order_item_details['name']}}
								@endif
							</td>
							<td>{{ $item->price }} {{ $currency }}</td>
							<td>{{ $item->discount_amount }} {{ $currency }}</td>
							<td>{{ $item->total_price_minus_discounts }} {{ $currency }}</td>
							<td>{{ $item->tax }} {{ $currency }}</td>
							<td>{{ $item->tax_rate }}</td>
							<td>{{$item->quantity}}</td>
							<td>{{ $item->total_price }}</td>

						</tr>
						@endforeach
					</tbody>
			</table>


			</div>
			<div class="tab-pane fade" id="btabs-shipping" role="tabpanel">

			<table class="table">
				<thead>
					<tr><th colspan="2" ><h4>{{__('addresses.type-delivery') }}</h4></th></tr>

				</thead>
				<tbody>
					<tr>
						<th scope="row">{{ __('addresses.name' )}}</th>
						<td>{{ $order->delivery_address['name'] }}</td>
					</tr>
					<tr>
						<th scope="row">{{ __('addresses.street' )}}</th>
						<td>{{ $order->delivery_address['street'] }}</td>
					</tr>
					<tr>
						<th scope="row">{{ __('addresses.city' )}}</th>
						<td>{{ $order->delivery_address['city'] }}</td>
					</tr>
					<tr>
						<th scope="row">{{ __('addresses.zip_code' )}}</th>
						<td>{{ $order->delivery_address['zip_code'] }}</td>
					</tr>
					<tr>
						<th scope="row">{{ __('addresses.country' )}}</th>
						<td>{{ \App\Models\Address::getCountryFullName($order->delivery_address['country_code']) }}</td>
					</tr>
					<tr>
						<th scope="row">{{ __('addresses.phone' ) }}</th>
						<td>{{ $order->delivery_address['phone'] ?? '-' }}</td>
					<tr>
						<th scope="row">{{ __('addresses.note' )}}</th>
						<td>{{ $order->delivery_address['note'] ?? '-' }}</td>
					</tr>
				</tbody>
			</table>

			@if($order->invoice_address)
				<table class="table">
						<thead>
							<tr><th colspan="2" ><h4>{{__('addresses.type-invoice') }}</h4></th></tr>

						</thead>
						<tbody>
							<tr>
								<th scope="row">{{ __('addresses.name' )}}</th>
								<td>{{ $order->invoice_address['name'] }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('addresses.street' )}}</th>
								<td>{{ $order->invoice_address['street'] }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('addresses.city' )}}</th>
								<td>{{ $order->invoice_address['city'] }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('addresses.zip_code' )}}</th>
								<td>{{ $order->invoice_address['zip_code'] }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('addresses.country' )}}</th>
								<td>{{ \App\Models\Address::getCountryFullName($order->invoice_address['country_code']) }}</td>
							</tr>
							<tr>
								<th scope="row">{{ __('addresses.phone' ) }}</th>
								<td>{{ $order->invoice_address['phone'] ?? '-' }}</td>
							<tr>
								<th scope="row">{{ __('addresses.oib' )}}</th>
								<td>{{ $order->invoice_address['oib'] ?? '-' }}</td>
							</tr>
						</tbody>
				</table>
			@endif
			</div>
			<div class="tab-pane fade" id="btabs-media" role="tabpanel">
			@if(count($order->storage()->files('virman')) > 0)
					@foreach($order->storage()->files('virman') as $file)
						<img src="{{ $file->getUrl() }}" class="img-thumbnail" loading="lazy">
					@endforeach
			@else
				<div>No files</div>
			@endif
			</div>

</div>
				</div>

@endsection
