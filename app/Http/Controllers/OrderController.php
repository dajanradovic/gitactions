<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;
use App\Models\OrderShipment;
use App\Http\Requests\UpdateOrder;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
	public function index(): View
	{
		$orders = Order::cmsAvailable()->latest()->get();

		return view('orders.list', compact('orders'));
	}

	public function edit(Order $id): View
	{
		$order = $id;

		$customer = $order->customer;
		$orderItems = $order->orderItems;

		return view('orders.add', compact('order', 'customer', 'orderItems'));
	}

	public function update(UpdateOrder $request, Order $id): RedirectResponse
	{
		if ($request->shipments) {
			foreach ($request->shipments as $index => $value) {
				if ($value) {
					$shipment = OrderShipment::findOrFail($index);
					$shipment->update(['shipment_number' => $value]);
				}
			}
		}

		$id->update([
			'ready_for_pickup' => $request->boolean('ready_for_pickup'),
			'status' => $request->status,
			'payment_id' => $request->payment_id
		]);

		return $this->redirectFromSave('orders.list');
	}
}
