<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\DeliveryPrice;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreDeliveryPrice;

class DeliveryPriceController extends Controller
{
	public function index(): View
	{
		$delivery_prices = DeliveryPrice::orderBy('country_code')->get();

		return view('delivery-prices.list', compact('delivery_prices'));
	}

	public function edit(DeliveryPrice $id): View
	{
		$deliveryPrice = $id;

		return view('delivery-prices.add', compact('deliveryPrice'));
	}

	public function update(StoreDeliveryPrice $request, DeliveryPrice $id): RedirectResponse
	{
		$id->update([
			'up_to_2_kg' => $request->up_to_2_kg,
			'up_to_5_kg' => $request->up_to_5_kg,
			'up_to_10_kg' => $request->up_to_10_kg,
			'up_to_15_kg' => $request->up_to_15_kg,
			'up_to_20_kg' => $request->up_to_20_kg,
			'up_to_25_kg' => $request->up_to_25_kg,
			'up_to_32_kg' => $request->up_to_32_kg,
			'islands_extra' => $request->islands_extra ?? 0,
			'additional_costs' => $request->additional_costs
		]);

		return $this->redirectFromSave('delivery-prices.list');
	}
}
