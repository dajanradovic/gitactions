<?php

namespace App\Services\Orders\Payments;

use App\Models\Order;
use App\Jobs\CreateOrderInErp;
use App\Jobs\CreateInvoiceAndSendEmail;
use App\Contracts\OrderFinalizationInterface;

class PayOnDelivery implements OrderFinalizationInterface
{
	public function finalize(Order $order): void
	{
		$this->updateOrder($order);
		CreateInvoiceAndSendEmail::dispatch($order);
		CreateOrderInErp::dispatch($order);
	}

	public function updateOrder(Order $order): void
	{
		$order->update([
			'status' => ORDER::STATUS_PAID,
			'order_number' => $order->createOrderNumber()
		]);
	}
}
