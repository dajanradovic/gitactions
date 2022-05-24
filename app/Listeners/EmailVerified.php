<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Verified;

class EmailVerified
{
	public function handle(Verified $event): void
	{
		if ($event->user instanceof User) {
			config([
				'fortify.redirects.email-verification' => $event->user->user->verifyEmailRedirectUrl()
			]);
		}
	}
}
