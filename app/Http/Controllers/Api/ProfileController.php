<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Session;
use App\Http\Requests\OAuthToken;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreAddress;
use App\Http\Requests\UpdateAvatar;
use App\Services\Auth\OAuthHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\UpdateProfile;
use App\Http\Requests\ChangePassword;
use App\Http\Resources\OrderResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\SessionResource;
use App\Http\Resources\ReferralResource;
use App\Http\Requests\SearchUserActivity;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\UserActivityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileController extends Controller
{
	public function me(): JsonResource
	{
		return auth()->user()->getModelResource();
	}

	public function sessions(): JsonResource
	{
		return SessionResource::collection(auth()->user()->sessions()->orderBy('last_activity', 'desc')->simplePaginate());
	}

	public function wishlist(): JsonResource
	{
		return ProductListResource::collection(Product::liked()->paginate());
	}

	public function activities(SearchUserActivity $request): JsonResource
	{
		$type = $request->type;

		return UserActivityResource::collection(auth()->user()
			->activities()
			->includes()
			->when($type, function ($query) use ($type): void {
				$query->where('type', $type);
			})
			->search($request->search)
			->latest()
			->paginate()
			->withQueryString());
	}

	public function removeSession(Session $id): JsonResponse
	{
		if ($id->user_id != auth()->user()->id) {
			abort(403);
		}

		return response()->json(['data' => [
			'deleted' => $id->delete(),
		]]);
	}

	public function logout(LogoutRequest $request): JsonResponse
	{
		auth()->logout();

		if ($request->device_id) {
			$request->user()->devices()->where('device_id', $request->device_id)->delete();
		}

		return response()->json(['data' => [
			'status' => true,
		]]);
	}

	public function remove(): JsonResponse
	{
		return response()->json(['data' => [
			'deleted' => auth()->user()->delete(),
		]]);
	}

	public function update(UpdateProfile $request): JsonResource
	{
		$user = $request->user();

		$user->update([
			'name' => $request->name,
			'email' => $request->email,
			'timezone' => $request->timezone,
		]);

		$user->user->update([
			'surname' => $request->surname,
			'oib' => $request->oib,
			'date_of_birth' => $request->date_of_birth,
			'company_name' => $request->company_name,
			'newsletter' => $request->boolean('newsletter'),
		]);

		$user->user->update($user->user->updateProfileLogic($request));

		return $user->getModelResource();
	}

	public function updatePassword(ChangePassword $request): JsonResource
	{
		$user = $request->user();

		$user->update([
			'password' => $request->password,
		]);

		return $user->getModelResource();
	}

	public function updateAvatar(UpdateAvatar $request): JsonResource
	{
		$user = $request->user();

		$user->update([
			'avatar' => $user->storage()->deleteFiles('avatar')->handle()->getFirstThumb('avatar')
		]);

		return $user->getModelResource();
	}

	public function connectOAuth(OAuthToken $request, string $driver): JsonResource
	{
		$oAuthHandler = new OAuthHandler;
		$oAuthUser = $oAuthHandler->getUser($driver, $request->access_token, true);

		if (!$oAuthUser) {
			abort(401, $oAuthHandler->getLastExceptionMessage());
		}

		$user = $request->user();
		$syncData = $request->boolean('sync_data');

		$user->update([
			$driver => $oAuthUser->getId(),
			'name' => $syncData ? $oAuthUser->getName() : $user->name,
			'email' => $syncData ? $oAuthUser->getEmail() : $user->email,
			'avatar' => $syncData ? $oAuthUser->getAvatar() : $user->getAvatar()
		]);

		return $user->getModelResource();
	}

	public function disconnectOAuth(string $driver): JsonResource
	{
		$user = auth()->user();

		$user->update([
			$driver => null
		]);

		return $user->getModelResource();
	}

	public function createAddress(StoreAddress $request): JsonResource
	{
		$address = getUser()->user->addresses()->updateOrCreate(['type' => $request->type], [
			'name' => $request->name,
			'country_code' => $request->country_code,
			'street' => $request->street,
			'city' => $request->city,
			'zip_code' => $request->zip_code,
			'phone' => $request->phone
		]);

		return new AddressResource($address);
	}

	public function addresses(): JsonResource
	{
		return AddressResource::collection(getUser()->user->addresses);
	}

	public function referrals(): JsonResource
	{
		$customer = auth()->user()->user;

		return ReferralResource::collection($customer->referrals()->latest()->paginate());
	}

	public function orders(): JsonResource
	{
		return OrderResource::collection(getUser()->user->orders()->includes()->available()->latest()->paginate());
	}
}
