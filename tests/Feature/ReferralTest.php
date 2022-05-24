<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReferralTest extends TestCase
{
	public function testReferral(): void
	{
		$customer_referrer = $this->getCustomer();

		$email = $this->faker->email();

		$response = $this->actingAs($customer_referrer)->postJson(route('api.referrals.create'), [
			'email' => $email
		]);

		$response->assertCreated();

		$response = $this->postJson(route('api.auth.register.standard'), [
			'name' => $this->faker()->name(),
			'surname' => $this->faker()->name(),
			'email' => $email,
			'password' => 'test1234',
			'password_confirmation' => 'test1234'
		]);

		$response->assertCreated();

		$response = $this->actingAs($customer_referrer)->getJson(route('api.me.referrals'));

		$response->assertOk()->assertJson([
			'data' => [
				[
					'has_accepted' => true
				]
			]
		]);
	}

	public function testReferralDuplicate(): void
	{
		$customer_referrer = $this->getCustomer();
		$customer_dup = $this->getCustomer();

		$email = $customer_dup->email;

		$response = $this->actingAs($customer_referrer)->postJson(route('api.referrals.create'), [
			'email' => $email
		]);

		$response->assertForbidden();
	}
}
