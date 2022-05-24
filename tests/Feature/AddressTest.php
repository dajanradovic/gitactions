<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Address;

class AddressTest extends TestCase
{
	private ?User $user = null;

	public function setUp(): void
	{
		parent::setUp();
		$this->user = $this->getCustomer();
	}

	public function testCreateAddress(): void
	{
		$address = Address::factory()->make()->toArray();

		$response = $this->withToken($this->user->token())->postJson(route('api.me.create-address'), $address);

		$response->assertJsonPath('data.name', $address['name'])
				 ->assertJsonPath('data.type', $address['type'])
				 ->assertJsonPath('data.city', $address['city'])
				 ->assertJsonPath('data.street', $address['street']);

		$response->assertStatus(201);

		$this->assertDatabaseHas('addresses', [
			'customer_id' => $this->user->user->id,
			'type' => $address['type'],
			'country_code' => $address['country_code']
		]);
	}

	public function testUpdateAddress(): void
	{
		$address = Address::factory()->create(['customer_id' => $this->user->user->id]);

		$oldStreet = $address->street;

		$address->street = 'Test street';

		$response = $this->withToken($this->user->token())->postJson(route('api.me.create-address'), $address->toArray());

		$response->assertJsonPath('data.name', $address['name'])
				 ->assertJsonPath('data.type', $address['type'])
				 ->assertJsonPath('data.city', $address['city'])
				 ->assertJsonPath('data.street', $address['street']);

		$response->assertStatus(200);

		$this->assertDatabaseMissing('addresses', [
			'customer_id' => $this->user->user->id,
			'type' => $address['type'],
			'street' => $oldStreet
		]);

		$this->assertDatabaseHas('addresses', [
			'customer_id' => $this->user->user->id,
			'type' => $address['type'],
			'street' => 'Test street'
		]);
	}
}
