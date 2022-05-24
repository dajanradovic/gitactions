<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReferralMail extends Notification implements ShouldQueue
{
	use Queueable;

	private string $referrer;
	private string $email;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(string $referrer, string $email)
	{
		$this->referrer = $referrer;
		$this->email = $email;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array
	 */
	public function via(object $notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail(object $notifiable)
	{
		return (new MailMessage)
					->subject('You\'ve been invited to ' . setting('app_name'))
					->line($this->referrer . ' has invited you to start using ' . setting('app_name') . '!')
					->line(__('referrals.mail-points'))
					->action('Register!', url(config('custom.frontend_registration_url'), ['email' => $this->email]))
					->line('Thank you for using our application!');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array
	 */
	public function toArray(object $notifiable)
	{
		return [

		];
	}
}
