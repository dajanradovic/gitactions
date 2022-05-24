<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOfferMail extends Mailable
{
	use Queueable, SerializesModels;

	public Order $order;
	public string $company_name;
	public string $iban;
	public string $sifra_namjene;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
		$this->company_name = setting('company_name') ?? null;
		$this->iban = setting('iban') ?? null;
		$this->sifra_namjene = setting('sifra_namjene') ?? null;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$subject = __('emails.new-offer') . ' *' . formatLocalTimestamp($this->order->created_at, 'U') . '*';

		$mailMessage = $this->view('emails.new-offer')->subject($subject);

		if (app()->isLocal()) {
			$mailMessage->attach($this->order->storage()->getFirstFile('virman')->getPath());
			$mailMessage->attach($this->order->storage()->getFirstFile('offer')->getPath());
		} else {
			$mailMessage->attachFromStorageDisk(setting('media_storage'), $this->order->storage()->getFirstFile('virman')->getPath());
			$mailMessage->attachFromStorageDisk(setting('media_storage'), $this->order->storage()->getFirstFile('offer')->getPath());
		}

		return $mailMessage;
	}
}
