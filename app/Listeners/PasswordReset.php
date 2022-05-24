<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;

class PasswordReset
{
	public function handle(PasswordResetEvent $event): void
	{
		if ($event->user instanceof User) {
			config([
				'fortify.redirects.password-reset' => $event->user->user->passwordResetRedirectUrl()
			]);
		}
	}
}
