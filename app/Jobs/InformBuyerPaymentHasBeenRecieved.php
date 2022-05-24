<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use App\Mail\PaymentRecievedMail;
use App\Jobs\Middleware\BindConfigs;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Orders\InvoicesAndOffers\InvoiceMaker;

class InformBuyerPaymentHasBeenRecieved implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	public ?Order $order = null;

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
		$this->order->createInvoicePdf(new InvoiceMaker);

		Mail::to($this->order->customer_email)->send(new PaymentRecievedMail($this->order));
	}
}
