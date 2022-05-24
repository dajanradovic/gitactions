<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicApiTest extends TestCase
{
	public function testGetConfig(): void
	{
		$response = $this->getJson(route('api.config'));

		$response->assertOk();
	}

	public function testGetTags(): void
	{
		$response = $this->getJson(route('api.tags.list'));

		$response->assertOk();
	}

	public function testGetNotifications(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->getJson(route('api.notifications.list'));

		$response->assertOk();
	}

	public function testCreateAndDeleteDevice(): void
	{
		$user = $this->getUser();
		$token = $user->token();

		$response = $this->withToken($token)->postJson(route('api.devices.store'), [
			'device_id' => $this->faker()->uuid()
		]);

		$response->assertCreated();

		$response = $this->withToken($token)->getJson(route('api.devices.list'));

		$response->assertOk();

		$response = $this->withToken($token)->deleteJson(route('api.devices.remove', $response->json('data')[0]['device_id']));

		$response->assertOk()->assertJsonPath('data.deleted', true);
	}

	public function testDeleteAllDevices(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->deleteJson(route('api.devices.remove-all'));

		$response->assertOk()->assertJsonPath('data.deleted', true);
	}

	public function testDryRunHeader(): void
	{
		$user = $this->getUser();

		$dryRunHeaderName = config('custom.dry_run_header_name');

		$response = $this->withHeader($dryRunHeaderName, '1')->withToken($user->token())->getJson(route('api.me.get'));

		$response->assertNoContent()->assertHeader($dryRunHeaderName, 1);
	}
}
