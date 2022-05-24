<?php

namespace App\Models;

use App\Traits\Countries;

class DeliveryPrice extends Model
{
	use Countries;

	public const DPD = 'DPD';
	public const GLS = 'GLS';

	public const SINGLE_SHIPMENT_MAX_WEIGHT = 31.5;

	public const ISLAND_ZIP_CODES = [
		20221, 20222, 20223, 20224, 20225, 20226, 20230, 20240, 20242, 20243, 20244, 20245, 20246, 20247, 20248, 20250, 20260, 20261,
		20262, 20263, 20264, 20267, 20269, 20270, 20271, 20272, 20273, 20274, 20275, 20290, 21225, 21400, 21403, 21404, 21405, 21410,
		21412, 21413, 21420, 21423, 21424, 21425, 21426, 21430, 21432, 21450, 21454, 21460, 21462, 21463, 21465, 21466, 21467, 21469,
		21480, 21483, 21485, 22232, 22233, 22234, 22235, 22236, 22240, 22240, 22241, 22242, 22243, 22244, 23212, 23248, 23249, 23250,
		23251, 23262, 23263, 23264, 23271, 23272, 23273, 23274, 23275, 23281, 23282, 23283, 23284, 23285, 23286, 23287, 23291, 23292,
		23293, 23294, 23295, 23296, 51280, 51281, 51500, 51511, 51512, 51513, 51514, 51515, 51516, 51517, 51521, 51522, 51523, 51550,
		51550, 51551, 51552, 51553, 51554, 51555, 51556, 51557, 51559, 51561, 51562, 51564, 53291, 53294, 53296];

	public static function getShippingServices(): array
	{
		return [
			self::DPD,
			self::GLS
		];
	}

	public function getPrice(float $weight, string $country, string $provider): array
	{
		$numberOfPackages = 0;
		$shipmentPrice = 0;

		while ($weight > self::SINGLE_SHIPMENT_MAX_WEIGHT) {
			$numberOfPackages++;
			$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_32_kg;

			$weight = $weight - self::SINGLE_SHIPMENT_MAX_WEIGHT;
		}

		$numberOfPackages++;

		switch ($weight) {

			case $weight <= 2:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_2_kg;

				break;

			case $weight <= 5:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_5_kg;

				break;

			case $weight <= 10:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_10_kg;

				break;

			case $weight <= 15:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_15_kg;

				break;

			case $weight <= 20:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_20_kg;

				break;

			case $weight <= 25:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_25_kg;

				break;

			case $weight <= 31.5:
				$shipmentPrice += DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->up_to_32_kg;

				break;
		}

		return [
			'number_of_packages' => $numberOfPackages,
			'price' => $shipmentPrice
		];
	}

	public function getAdditionalIslandCosts(string $country, string $provider): int|float|null
	{
		return DeliveryPrice::where('country_code', $country)->where('delivery_service', $provider)->first()->islands_extra;
	}
}
