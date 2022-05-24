<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\App;
use App\Contracts\OrderFinalizationInterface;
use App\Services\Orders\Payments\GenerateSlip;
use App\Services\Orders\Payments\PayOnDelivery;
use App\Services\Orders\Payments\CorvusPaymentService;
use App\Services\Orders\Payments\PayPalPaymentService;

class OrderFinalizationServiceFactory
{
	/**
	 * @return CorvusPaymentService|GenerateSlip|PayOnDelivery|PayPalPaymentService|null
	 */
	public static function make(int $type, ?int $provider = null): ?OrderFinalizationInterface
	{
		switch ($type) {

				case Order::PAYMENT_METHOD_CARD:

						switch ($provider) {

							case Order::PAYMENT_PROVIDER_PAYPAL: return new PayPalPaymentService;

							case Order::PAYMENT_PROVIDER_CORVUS: return new CorvusPaymentService;
						}

				case Order::PAYMENT_METHOD_VIRMAN: return App::make(GenerateSlip::class);

				case Order::PAYMENT_METHOD_POUZECE: return new PayOnDelivery;

				}

		return null;
	}
}
