<?php

namespace App\Http\Resources;

use App\Services\Orders\Shipping\ShippingServiceFactory;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Model
 * */
class ShipmentResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function toArray($request): array
	{
		$shippingServiceFactory = new ShippingServiceFactory;

		return [
			'id' => $this->id,
			'shipment_number' => $this->shipment_number,
			'delivery_service' => $this->delivery_service,
			'tracking_link' => $shippingServiceFactory->make($this->delivery_service)->createTrackingLink($this->shipment_number),
			'created_at' => formatTimestamp($this->created_at),
			'updated_at' => formatTimestamp($this->updated_at),
		];
	}
}
