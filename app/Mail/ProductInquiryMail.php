<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductInquiryMail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct(public string $fromUser, public string $messageString, public int $type, public string $productName, public string $productCode)
	{
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(): self
	{
		$subject = $this->type == Product::VINE_HARVEST_INQUIRY
								? __('emails.upit-o-godistu-vina') . ' ' . time()
								: __('emails.upit-o-dostupnosti') . ' ' . time();

		return $this->to('dajan@lloyds.design'/* setting('app_email') */, setting('app_name'))
				->replyTo($this->fromUser)->view('emails.product-inquiry')->subject($subject);
	}
}
