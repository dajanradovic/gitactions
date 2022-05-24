<?php

namespace App\Observers;

use App\Models\Order;
use App\Jobs\CreateOrderInErp;
use App\Jobs\InformBuyerPaymentHasBeenRecieved;

class OrderObserver
{
	public function deleting(Order $order): void
	{
		foreach ($order->orderItems as $item) {
			$item->delete();
		}

		foreach ($order->shipments as $shipment) {
			$shipment->delete();
		}
	}

	public function updating(Order $order): void
	{
		if ($order->status == Order::STATUS_PAID) {
			if (!$order->order_number) {
				$order->order_number = $order->createOrderNumber();	// if order was in status offer-sent and it was changed to status paid throuhgh cms
			}

			if ($order->getOriginal('status') == Order::STATUS_OFFER_SENT) { // if order was in status offer-sent and it was changed to status paid throuhgh cms

				InformBuyerPaymentHasBeenRecieved::dispatch($order);
				CreateOrderInErp::dispatch($order);
			}
		}
	}

	public function updated(Order $order): void
	{
		if ($order->wasChanged('payment_id') && !$order->payment_created_at) { // if payment_id was entered through cms
			$order->update(['payment_created_at' => formatTimestamp()]);
		}
	}
}
