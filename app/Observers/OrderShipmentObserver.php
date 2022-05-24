<?php

namespace App\Observers;

use App\Models\OrderShipment;
use App\Notifications\ShippingNumberUpdated;
use App\Services\Orders\Shipping\ShippingServiceFactory;

class OrderShipmentObserver
{
	public function updated(OrderShipment $orderShipment): void
	{
		if ($orderShipment->wasChanged('shipment_number') && $orderShipment->shipment_number) {
			$shippingServiceFactory = new ShippingServiceFactory;

			$order = $orderShipment->order;

			$order->notify(new ShippingNumberUpdated($orderShipment->shipment_number, $shippingServiceFactory->make($orderShipment->delivery_service), $order->order_number));
		}
	}
}
