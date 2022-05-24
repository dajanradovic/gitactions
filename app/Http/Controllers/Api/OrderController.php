<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Requests\StoreOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CalculateTotal;
use App\Http\Resources\OrderResource;
use App\Http\Requests\GuestCalculateTotal;
use App\Http\Resources\CalculationResource;
use App\Services\Orders\OrderCreationService;
use App\Services\Orders\OrderCalculationService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Orders\OrderFinalizationServiceFactory;

class OrderController extends Controller
{
	public function guestCalculate(GuestCalculateTotal $request, OrderCalculationService $orderCalculationService): JsonResource
	{
		return new CalculationResource($orderCalculationService->calculateTotal($request->items));
		// return response()->json(['data' => $orderCalculationService->calculateTotal($request->items)]);
	}

	public function calculate(CalculateTotal $request, OrderCalculationService $orderCalculationService, OrderCreationService $orderCreationService): JsonResource
	{
		$address = $orderCreationService->createBuyerAddress($request);

		return new CalculationResource($orderCalculationService->calculateTotal($request->items, $request->coupon_code, null, $address, getUser()?->user, $request->shipping_provider));

		// return response()->json(['data' => $orderCalculationService->calculateTotal($request->items, $request->coupon_code, null, $address, $user, $request->shipping_provider)]);
	}

	public function create(StoreOrder $request, OrderCalculationService $orderCalculationService, OrderCreationService $orderCreationService): JsonResource
	{
		$address = $orderCreationService->createBuyerAddress($request);

		$calculation = $orderCalculationService->calculateTotal($request->items, $request->coupon_code, null, $address, getUser()?->user, $request->shipping_provider);

		try {
			DB::beginTransaction();

			$order = $orderCreationService->create($request, $calculation, getUser()->user ?? null, $request->shipping_provider ?? null);

			OrderFinalizationServiceFactory::make($request->payment_method, $request->payment_card_provider)->finalize($order);

			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
			 dd($e->getMessage());
			throw new Exception('Something went wrong. Please try again');
		}

		return new OrderResource($order);
	}
}
