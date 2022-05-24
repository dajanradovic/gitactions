<?php

namespace App\Services\Orders\Shipping;

use Exception;
use App\Models\DeliveryPrice;
use App\Contracts\ShippingProviderInterface;

class ShippingServiceFactory
{
	/**
	 * @return DpdShippingService
	 */
	public static function make(string $service): ShippingProviderInterface
	{
		switch ($service) {

			case 'DPD':
				return new DpdShippingService(new Deliveryprice);

			/*case 'GLS':
				return new GlsShippingService;*/

	}

		throw new Exception('Such provider does not exists');
	}
}
