<?php

namespace App\Http\Resources;

use App\Http\Resources\ShipmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Order
 *
 * @property array $paypal
 * @property array $corvus
 * */
class OrderResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'status' => $this->status,
			'payment_type' => $this->payment_type,
			'payment_id' => $this->payment_id,
			'payment_created_at' => $this->payment_created_at ? formatTimestamp($this->payment_created_at) : null,
			'ready_for_pickup' => $this->ready_for_pickup,
			'customer_email' => $this->customer_email,
			'calculation' => [
				'total_price' => $this->total_price,
				'total_discounts' => $this->total_discounts,
				'total_price_minus_discounts' => $this->total_price_minus_discounts,
				'tax_total' => $this->tax_total,
				'shipping' => $this->shipping_price,
				'final_price' => $this->final_price
			],
			'currency' => $this->currency,
			'reference_number' => $this->reference_number,
			'shipments' => ShipmentResource::collection($this->shipments),
			'corvus' => $this->when((bool) $this->corvus, $this->corvus),
			'paypal' => $this->when((bool) $this->paypal, $this->paypal),
			'order_items' => OrderItemResource::collection($this->orderItems),
			'delivery_address' => $this->delivery_address,
			'invoice_address' => $this->invoice_address,
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
