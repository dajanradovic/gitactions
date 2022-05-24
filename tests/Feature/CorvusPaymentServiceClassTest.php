<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Orders\Payments\CorvusPaymentService;

class CourvusPaymentServiceClassTest extends TestCase
{
	public function testOrderHasCorvusFieldsAppended(): void
	{
		$order = Order::factory()->has(OrderItem::factory()->count(3))->create();

		$corvusPaymentService = new CorvusPaymentService('v1', '964fec0b-e9fd-4e66-bace-65e676e9c5f2', 'hr', 'hrk', 'secret', 'true');

		$corvusPaymentService->finalize($order);

		$this->assertArrayHasKey('corvus', $order->toArray());
		$this->assertArrayHasKey('corvus_order_number', $order->toArray()['corvus']);
		$this->assertArrayHasKey('cart', $order->toArray()['corvus']);
		$this->assertArrayHasKey('total_price', $order->toArray()['corvus']);
		$this->assertArrayHasKey('corvus_signature', $order->toArray()['corvus']);

		$this->assertIsFloat($order->corvus['total_price']);
		$this->assertIsString($order->corvus['cart']);
		$this->assertIsString($order->corvus['corvus_order_number']);
		$this->assertIsString($order->corvus['corvus_signature']);

		$this->assertNotEmpty($order->corvus['cart']);
		$this->assertNotEmpty($order->corvus['corvus_order_number']);
		$this->assertNotEmpty($order->corvus['corvus_signature']);
	}
}
