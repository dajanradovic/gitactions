<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReferralAcceptedMail extends Notification implements ShouldQueue
{
	use Queueable;

	private string $referrer;
	private string $referred;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(string $referrer, string $referred)
	{
		$this->referrer = $referrer;
		$this->referred = $referred;
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
					->subject('Referral Accepted!')
					->line($this->referrer . ' has accepted ' . $this->referred . ' invitation!')
					->action('Go to ' . setting('app_name'), url(config('custom.frontend_base_url')))
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
