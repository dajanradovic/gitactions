<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\NewOfferMail;
use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Orders\InvoicesAndOffers\OfferMaker;

class CreateOfferAndSendEmail implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public ?Order $order = null;

	public bool $deleteWhenMissingModels = true;

	/**
	 * Create a new job instance.
	 */
	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	public function middleware(): array
	{
		return [new BindConfigs];
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$this->order->createInvoicePdf(new OfferMaker);

		Mail::to($this->order->customer_email)->send(new NewOfferMail($this->order));
	}
}
