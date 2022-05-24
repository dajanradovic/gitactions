@extends('layouts.public')

@push('scripts')
	<script src="https://www.paypal.com/sdk/js?currency=EUR&client-id={{ setting('paypal_sandbox_client_id') }}"></script>
@endpush

@section('title', __('orders.payment-title', ['n' => $order->reference_number]))

@section('content')
	<div class="login login-2 login-signin-on d-flex flex-row-fluid">
		<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('{{ asset('img/login.jpg') }}');">
			<div class="login-form text-center p-7 position-relative overflow-hidden">
				<!--begin::Login Header-->
				<div class="d-flex flex-center mb-5">
					<img src="{{ asset('img/logo.png') }}" class="max-h-100px" alt="Logo">
				</div>
				<!--end::Login Header-->

				<!--begin::Body-->
				<div class="login-signin">
					<div class="mb-10">
						<h3>{{ __('orders.payment-title', ['n' => $order->reference_number]) }}</h3>
						<div class="text-muted font-weight-bold">{{ __('orders.complete-order') }}</div>
					</div>

					<table width="100%" class="table table-head-custom text-left">
						<thead>
							<tr>
								<th>{{ __('orders.order-items') }}</th>
								<th>{{ __('orders.price') }}</th>
							</tr>
						</thead>
						<tbody>

							<tr>
								<td>{{ __('orders.price-before-discounts') }}</td>
								<td>{{ $order->total_price }} {{ $order->currency }}</td>
							</tr>
							<tr>
								<td>{{ __('orders.discounts') }}</td>
								<td>{{ $order->total_discounts }} {{ $order->currency }}</td>
							</tr>
							<tr>
								<td>{{ __('orders.price-after-discounts') }}</td>
								<td>{{ $order->total_price_minus_discounts }} {{ $order->currency }}</td>
							</tr>
							@if($order->isShippingOrder())
								<tr>
									<td>{{ __('orders.shipping') }}</td>
									<td>{{ $order->shipping_price }} {{ $order->currency }}</td>
								</tr>
							@endif
							@if($order->isShippingOrder())
								<tr>
									<td>{{ __('orders.shipping') }}</td>
									<td>{{ $order->shipping_price }} {{ $order->currency }}</td>
								</tr>
							@endif

						</tbody>
						<tfoot>
							<tr>
								<td class="total-paypal"><strong>{{ __('orders.final-price') }}</strong></td>
								<td class="total-paypal"><strong>{{ $order->final_price }} {{$order->currency}}</strong></td>
							</tr>
							@if($order->currency == 'HRK')
							<tr>
								<td class="total-paypal"><strong>{{ __('orders.final-price-in-eur') }}</strong></td>
								<td class="total-paypal"><strong>{{ $order->paypalAmount}} EUR</strong></td>
							</tr>
							@endif
						</tfoot>
					</table>

					<span id="paypal-order-data" data-verify-url="{{ route('api.webhooks.paypal.verify', ['id' =>  $order->id ]) }}"  data-error-url="{{ setting('corvus_cancel_url') }}" data-amount="{{ $order->paypalAmount }}"></span>
					<div class="mb-10" id="paypal-button-container"></div>
				</div>
				<!--end::Body-->
			</div>
		</div>
	</div>
@endsection
