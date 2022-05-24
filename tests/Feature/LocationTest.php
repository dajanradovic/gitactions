<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocationTest extends TestCase
{
	public function testGetCountries(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->getJson(route('api.location.countries', [
			'fields' => ['name']
		]));

		$response->assertOk()->assertJsonStructure([
			'data' => [
				'*' => [
					'name'
				]
			]
		]);
	}

	public function testGeocodeByAddress(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->getJson(route('api.location.address', [
			'address' => 'Rudarska 1, 52220 Labin'
		]));

		$response->assertOk()->assertJsonStructure([
			'data' => [
				'results'
			]
		]);
	}

	public function testGeocodeByLatLng(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->getJson(route('api.location.lat-lng', [
			'lat' => 45.0918506,
			'lng' => 14.1190614
		]));

		$response->assertOk()->assertJsonStructure([
			'data' => [
				'results'
			]
		]);
	}
}
