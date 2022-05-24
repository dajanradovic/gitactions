<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Orders\Payments\PayPalPaymentService;
use Illuminate\View\View;

class PayPalController extends Controller
{
	public function pay(Order $id, PayPalPaymentService $payPalPaymentService): View
	{
		if ($id->isPayPalVerified()) {
			abort(403);
		}

		$order = $id;

		$convertedAmount = $payPalPaymentService->convertToEur($order);

		$order->paypalAmount = $convertedAmount;
		
		return view('paypal.pay', compact('order'));
	}
}
