<?php

namespace App\Http\Controllers\Api;

use Notification;
use App\Models\User;
use App\Models\Referral;
use App\Notifications\ReferralMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReferral;
use App\Http\Resources\ReferralResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferralController extends Controller
{
	public function create(StoreReferral $request): JsonResource
	{
		$user = auth()->user();
		$customer = $user->user;

		$email = $request->email;

		if (User::where('email', $email)->exists() || Referral::where('email', $email)->exists()) {
			abort(403, 'Referral already sent');
		}

		$full_name = $customer->getFullName();

		$referral = $customer->createReferral($email);

		Notification::route('mail', $email)->notify(new ReferralMail($full_name, $email));

		return new ReferralResource($referral);
	}
}
