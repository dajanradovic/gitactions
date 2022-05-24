<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class MiddlewareTest extends TestCase
{
	public function testRestrictByUserTypeMiddleware(): void
	{
		$user = $this->getUser();

		Route::middleware(['api', 'auth', 'restrict_by_user_type:' . $user->user::class])->get('api/test-route', function (): JsonResponse {
			return response()->json();
		});

		$response = $this->withToken($user->token())->getJson('api/test-route');

		$response->assertOk();
	}

	public function testChangeLocaleMiddleware(): void
	{
		Route::middleware(['api', 'auth', 'change_locale'])->get('api/test-route', function (): JsonResponse {
			return response()->json();
		});

		$oldLocale = app()->getLocale();

		$user = $this->getUser([
			'locale' => 'hr'
		]);

		$response = $this->withToken($user->token())->getJson('api/test-route');

		$response->assertOk();

		$this->assertEquals(app()->getLocale(), 'hr');

		app()->setLocale($oldLocale);
	}
}
