<?php

namespace Tests;

use App\Models\User;

trait TestHelpers
{
	protected function getUser(array $extraData = []): User
	{
		return User::factory()->createOne($extraData);
	}

	protected function getCustomer(array $extraData = []): User
	{
		return User::factory()->asCustomer()->createOne($extraData);
	}

	protected function getInactiveUser(array $extraData = []): User
	{
		return User::factory()->inactive()->createOne($extraData);
	}

	protected function getUserWithDisallowedMethodsRole(array $methods = [], array $extraData = []): User
	{
		return User::factory()->withDisallowedMethodsRole($methods)->createOne($extraData);
	}

	protected function getUserWithForbiddenRouteRole(string $route, array $extraData = []): User
	{
		return User::factory()->withForbiddenRouteRole($route)->createOne($extraData);
	}
}
