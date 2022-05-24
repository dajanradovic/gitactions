<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Jobs\CreateOrderInErp;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Jobs\CreateInvoiceAndSendEmail;
use App\Services\Orders\Payments\CorvusPaymentService;

class CorvusController extends Controller
{
	public function success(Request $request, CorvusPaymentService $corvusPaymentService): RedirectResponse
	{
		// StoreCorvus

		$order = Order::where('payment_id', $request->order_number)->firstOrFail();

		$signature = $corvusPaymentService->calculateWebhookSignature($request->order_number, $request->language, $request->approval_code);

		if ($signature !== $request->signature) {
			return redirect()->away(setting('corvus_cancel_url'));
		}

		$order->update([
			'status' => Order::STATUS_PAID,
			'order_number' => $order->createOrderNumber(),
			'payment_created_at' => formatTimestamp()
		]);

		CreateInvoiceAndSendEmail::dispatch($order);
		CreateOrderInErp::dispatch($order);

		return redirect()->away(setting('corvus_success_url') . '?order_id=' . $order->id);
	}

	public function cancel(Request $request): RedirectResponse
	{
		// CancelCorvus
		$order = Order::where('payment_id', $request->order_number)->firstOrFail();
		$order->delete();

		return redirect()->away(setting('corvus_cancel_url'));
	}
}
