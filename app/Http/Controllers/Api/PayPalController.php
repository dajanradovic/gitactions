<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Jobs\CreateOrderInErp;
use App\Rules\VerifyPayPalPayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Jobs\CreateInvoiceAndSendEmail;
use Illuminate\Support\Facades\Validator;

class PayPalController extends Controller
{
	public function verify(Order $id, Request $request): RedirectResponse
	{
		if ($id->isPayPalVerified()) {
			abort(403);
		}

		$validator = Validator::make($request->all(), [
			'paypal_bill_id' => ['required', 'bail', 'string', 'max:50', new VerifyPayPalPayment($id->final_price)]
		]);

		if ($validator->fails()) {
			return redirect()->route('orders.failure', $id->id);
		}

		$id->update([
			'payment_id' => $request->query('paypal_bill_id'),
			'status' => ORDER::STATUS_PAID,
			'order_number' => $id->createOrderNumber(),
			'payment_created_at' => formatTimestamp()
		]);

		CreateInvoiceAndSendEmail::dispatch($id);
		CreateOrderInErp::dispatch($id);

		// potrebno izmijeniti return url
		return redirect()->away('https://www.google.hr' . '?order_id=' . $id->id);
	}
}
