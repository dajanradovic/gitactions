<?php

$all_statuses = [
	App\Models\Order::STATUS_CANCELED => __('orders.canceled'),
	App\Models\Order::STATUS_PAID => __('orders.paid'),
	App\Models\Order::STATUS_PENDING => __('orders.pending'),
	App\Models\Order::STATUS_REFUNDED => __('orders.refunded'),
	App\Models\Order::STATUS_OFFER_SENT => __('orders.offer-sent'),
];

$all_states = [
	App\Models\Order::STATUS_CANCELED => 'danger',
	App\Models\Order::STATUS_PAID => 'success',
	App\Models\Order::STATUS_CANCELED => 'warning',
	App\Models\Order::STATUS_PENDING => 'primary',
	App\Models\Order::STATUS_OFFER_SENT => 'info',

];

?>
<span class="label label-{{ $all_states[$status] ?? 'secondary' }} label-inline">{{ strtoupper($all_statuses[$status] ?? '-') }}</span>
