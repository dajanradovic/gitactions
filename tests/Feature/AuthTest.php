<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
	public function testValidLogin(): void
	{
		$user = $this->getUser();

		$response = $this->postJson(route('api.auth.login.standard'), [
			'email' => $user->email,
			'password' => 'test1234'
		]);

		$response->assertOk()->assertJsonPath('data.id', $user->user->id);
	}

	public function testInvalidLogin(): void
	{
		$response = $this->postJson(route('api.auth.login.standard'), [
			'email' => $this->faker()->safeEmail(),
			'password' => 'test1234'
		]);

		$response->assertUnauthorized();
	}

	public function testSilentLogin(): void
	{
		$user = $this->getUser();

		$response = $this->get($user->getSilentLoginUrl());

		$response->assertRedirect(route('home'));

		$this->assertAuthenticatedAs($user);
	}

	public function testRegister(): void
	{
		$email = $this->faker()->safeEmail();

		$response = $this->postJson(route('api.auth.register.standard'), [
			'name' => $this->faker()->name(),
			'surname' => $this->faker()->name(),
			'email' => $email,
			'password' => 'test1234',
			'password_confirmation' => 'test1234'
		]);

		$response->assertCreated()->assertJsonPath('data.email', $email);
	}

	public function testWithoutToken(): void
	{
		$response = $this->getJson(route('api.me.get'));

		$response->assertUnauthorized();
	}

	public function testInvalidToken(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token(['valid_until' => now()->subDay()]))->getJson(route('api.me.get'));

		$response->assertUnauthorized();

		$response = $this->withToken($user->token(['valid_from' => now()->addDay()]))->getJson(route('api.me.get'));

		$response->assertUnauthorized();
	}

	public function testGetMeForbidden(): void
	{
		$user = $this->getInactiveUser();

		$response = $this->withToken($user->token())->getJson(route('api.me.get'));

		$response->assertForbidden();
	}

	public function testBearerAuth(): void
	{
		$user = $this->getUser();

		$response = $this->withToken($user->token())->getJson(route('api.me.get'));

		$response->assertOk()->assertJsonPath('data.id', $user->user->id);
	}

	public function testQueryParamAuth(): void
	{
		$user = $this->getUser();

		$response = $this->getJson(route('api.me.get', [
			'token' => $user->token()
		]));

		$response->assertOk()->assertJsonPath('data.id', $user->user->id);
	}

	public function testBasicAuth(): void
	{
		$user = $this->getUser();

		$response = $this->withToken(base64_encode($user->{setting('basic_auth_username_field')} . ':test1234'), 'Basic')->getJson(route('api.me.get'));

		$response->assertOk()->assertJsonPath('data.id', $user->user->id);
	}

	public function testPasswordResetRequest(): void
	{
		$user = $this->getUser();

		$response = $this->postJson(route('api.auth.password.request-reset'), [
			'email' => $user->email
		]);

		$response->assertOk()->assertJsonPath('data.status', true);
	}

	public function testVerifyEmail(): void
	{
		$user = $this->getUser();

		$user->update([
			'email_verified_at' => null
		]);

		$url = url()->temporarySignedRoute('verification.verify', now()->addMinutes(config('auth.verification.expire')), [
			'id' => $user->getKey(),
			'hash' => sha1($user->getEmailForVerification())
		]);

		$response = $this->getJson($url);

		$response->assertOk()->assertJsonPath('data.id', $user->user->id);

		$this->assertTrue($user->fresh()->hasVerifiedEmail());
	}

	public function testResendEmailVerificationNotification(): void
	{
		$user = $this->getUser();

		$user->update([
			'email_verified_at' => null
		]);

		$response = $this->withToken($user->token())->postJson(route('verification.send'));

		$response->assertOk()->assertJsonPath('data.status', true);
	}
}
