<?php

namespace App\Providers;

use App\Listeners\EmailVerified;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use SocialiteProviders\Apple\AppleExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use App\Listeners\PasswordReset as PasswordResetListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class
		],
		Verified::class => [
			EmailVerified::class
		],
		PasswordReset::class => [
			PasswordResetListener::class
		],
		SocialiteWasCalled::class => [
			AppleExtendSocialite::class
		],
	];

	/**
	 * Register any events for your application.
	 */
	public function boot(): void
	{
	}
}
