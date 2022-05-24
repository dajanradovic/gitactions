<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Services\Orders\Payments\PayPalPaymentService;

class PayPalPaymentServiceClassTest extends TestCase
{
	public function testOrderHasPaypalFieldsAppended(): void
	{
		$order = Order::factory()->create();

		$payPalPaymentService = $this->getMockBuilder(PayPalPaymentService::class)
						->disableOriginalConstructor()
						->onlyMethods([])
						->getMock();

		$payPalPaymentService->finalize($order);

		$this->assertArrayHasKey('paypal', $order->toArray());
		$this->assertArrayHasKey('link', $order->toArray()['paypal']);

		$this->assertEquals(route('orders.pay', $order->id), $order->toArray()['paypal']['link']);
	}
}
