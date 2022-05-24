<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Customer;
use Laravel\Fortify\Fortify;
use App\Http\Requests\OAuthToken;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\VerifyEmail;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUser;
use App\Services\Auth\OAuthHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\RequestPasswordReset;
use App\Http\Requests\ResendEmailVerification;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class AuthController extends Controller
{
	public function login(LoginRequest $request): JsonResource
	{
		$user = User::includes()->where('email', $request->email)->loginLogic($request)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
			abort(401);
		}

		$user->fireLoginEvent('api');

		return $user->getModelResource();
	}

	public function register(RegisterUser $request): JsonResource
	{
		$user = Customer::create([
			'surname' => $request->surname,
			'oib' => $request->oib,
			'date_of_birth' => $request->date_of_birth,
			'company_name' => $request->company_name,
			'newsletter' => $request->boolean('newsletter'),
			'club_card' => $request->boolean('club_card'),
		])->authParent()->create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => $request->password,
			'timezone' => $request->timezone,
			'role_id' => setting('registration_api_role_id'),
			'allow_push_notifications' => false
		]);

		$oAuthDriver = (string) $request->oauth_driver;

		if ($oAuthDriver && ($oAuthUser = $this->getOAuthUserFromToken($oAuthDriver, $request->oauth_token))) {
			$user->update([
				$oAuthDriver => $oAuthUser->getId()
			]);
		}

		$cheeseClub = $user->user->makeCheeseClubMember();

		$user->user->update([
			'cheese_club_id' => $cheeseClub->id,
		]);

		$user->user->handleReferral($cheeseClub);

		return $user->getModelResource();
	}

	public function requestPasswordReset(RequestPasswordReset $request): JsonResponse
	{
		$status = null;

		try {
			$status = Password::broker(config('fortify.passwords'))->sendResetLink(['email' => $request->email]);
		} catch (Exception $e) {
			abort(422, $e->getMessage());
		}

		return response()->json([
			'data' => [
				'status' => $status == Password::RESET_LINK_SENT
			]
		]);
	}

	public function passwordReset(ResetPassword $request): JsonResponse
	{
		$data = [
			'email' => $request->email,
			'password' => $request->password,
			'password_confirmation' => $request->password_confirmation,
			'token' => $request->token,
		];

		$status = null;

		try {
			$status = Password::broker(config('fortify.passwords'))->reset($data, function (User $user) use ($data): void {
				app(ResetsUserPasswords::class)->reset($user, $data);
			});
		} catch (Exception $e) {
			abort(422, $e->getMessage());
		}

		return response()->json([
			'data' => [
				'status' => $status == Password::PASSWORD_RESET
			]
		]);
	}

	public function verifyEmail(VerifyEmail $request, User $id): JsonResource|RedirectResponse
	{
		if ($id->hasVerifiedEmail()) {
			return redirect()->intended(Fortify::redirects('email-verification'));
		}

		if ($id->markEmailAsVerified()) {
			event(new Verified($id));
		}

		return $request->wantsJson()
			? $id->getModelResource()
			: redirect()->intended(Fortify::redirects('email-verification'));
	}

	public function resendEmailVerificationNotification(ResendEmailVerification $request): JsonResponse|RedirectResponse
	{
		$request->user()->sendEmailVerificationNotification();

		return $request->wantsJson()
			? response()->json([
				'data' => [
					'status' => true
				]
			])
			: back()->with('status', 'verification-link-sent');
	}

	public function oauthLogin(OAuthToken $request, string $driver): JsonResource
	{
		$user = $this->getOAuthUserFromToken($driver, $request->access_token);

		if (!$user) {
			abort(401);
		}

		$user = User::has('user')->where($driver, $user->getId())->firstOrFail();

		$user->fireLoginEvent('api');

		return $user->getModelResource();
	}

	protected function getOAuthUserFromToken(string $driver, string $token): ?SocialiteUser
	{
		$oAuthHandler = new OAuthHandler;

		return $oAuthHandler->getUser($driver, $token, true);
	}
}
