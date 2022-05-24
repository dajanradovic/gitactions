<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use App\Jobs\Middleware\BindConfigs;
use App\Services\Support\ErpHandler;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateOrderInErp implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public bool $deleteWhenMissingModels = true;

	public int $tries = 3;

	/**
	 * Create a new job instance.
	 */
	public function __construct(private Order $order)
	{

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
		$erpHandler = new ErpHandler;

		$erpHandler->createOrder($this->order);

		// /ovdje treba jos logika za hendlanje response-a
	}
}
