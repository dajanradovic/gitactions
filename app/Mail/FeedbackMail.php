<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
	use Queueable, SerializesModels;

	protected User $user;
	protected string $message;

	/**
	 * Create a new message instance.
	 */
	public function __construct(User $user, string $message)
	{
		$this->user = $user;
		$this->message = $message;
	}

	/**
	 * Build the message.
	 */
	public function build(): self
	{
		return $this->to(setting('app_email'), setting('app_name'))
			->replyTo($this->user)
			->subject(__('emails.feedback'))
			->view('emails.feedback', [
				'user' => $this->user,
				'message' => $this->message
			]);
	}
}
