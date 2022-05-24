<?php

namespace App\Exceptions;

use Exception;

class OrderTooHeavyException extends Exception
{
	public function __construct()
	{
		parent::__construct(__('delivery-prices.package-too-heavy-note'), 500);
	}
}
