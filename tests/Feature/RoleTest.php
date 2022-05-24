<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoleTest extends TestCase
{
	public function testForbiddenMethod(): void
	{
		$user = $this->getUserWithDisallowedMethodsRole(['DELETE']);

		$response = $this->withToken($user->token())->deleteJson(route('api.me.remove'));

		$response->assertForbidden();
	}

	public function testForbiddenRoute(): void
	{
		$user = $this->getUserWithForbiddenRouteRole('api.me.remove');

		$response = $this->withToken($user->token())->deleteJson(route('api.me.remove'));

		$response->assertForbidden();
	}
}
