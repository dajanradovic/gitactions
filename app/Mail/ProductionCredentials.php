<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductionCredentials extends Mailable
{
	use Queueable, SerializesModels;

	protected User $user;
	protected string $password;

	/**
	 * Create a new message instance.
	 */
	public function __construct(User $user, string $password)
	{
		$this->user = $user;
		$this->password = $password;
	}

	/**
	 * Build the message.
	 */
	public function build(): self
	{
		return $this->to($this->user)
			->subject(__('emails.production-credentials', ['app_name' => setting('app_name')]))
			->view('emails.production-credentials', [
				'user' => $this->user,
				'password' => $this->password
			]);
	}
}
