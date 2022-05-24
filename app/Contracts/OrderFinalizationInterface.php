<?php

namespace App\Contracts;

use App\Models\Order;

interface OrderFinalizationInterface
{
	public function finalize(Order $order): void;
}
