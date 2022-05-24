<?php

namespace App\Services\Location;

use App\Services\Support\BaseApiHandler;

class GoogleMapGeocoder extends BaseApiHandler
{
	public const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

	protected string $apiKey;

	public function __construct(?string $apiKey = null)
	{
		parent::__construct();

		$this->apiKey = $apiKey ?? setting('google_api_key');
	}

	public function getLocationByAddress(string $address): ?array
	{
		$data = $this->client->get(self::API_URL, [
			'key' => $this->apiKey,
			'address' => $address
		]);

		return $this->returnResponse($data);
	}

	public function getAddressByLocation(float $lat, float $lng): ?array
	{
		$data = $this->client->get(self::API_URL, [
			'key' => $this->apiKey,
			'latlng' => $lat . ',' . $lng
		]);

		return $this->returnResponse($data);
	}
}
