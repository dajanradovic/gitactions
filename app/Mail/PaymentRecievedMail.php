<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentRecievedMail extends Mailable
{
	use Queueable, SerializesModels;

	public Order $order;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$subject = __('emails.payment-recieved') . ' *' . formatLocalTimestamp($this->order->created_at, 'U') . '*';

		$mailMessage = $this->view('emails.payment-recieved')->subject($subject);

		app()->isLocal() ? $mailMessage->attach($this->order->storage()->getFirstFile('invoice')->getPath())
						 : $mailMessage->attachFromStorageDisk(setting('media_storage'), $this->order->storage()->getFirstFile('invoice')->getPath());

		return $mailMessage;
	}
}
