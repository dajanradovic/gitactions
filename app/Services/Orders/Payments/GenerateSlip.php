<?php

namespace App\Services\Orders\Payments;

use App\Models\Order;
use App\Jobs\CreateOfferAndSendEmail;
use App\Contracts\OrderFinalizationInterface;
use App\Services\Orders\Payments\PaymentSlipCreation\BarCodeGenerator;
use App\Services\Orders\Payments\PaymentSlipCreation\PaymentSlipGenerator;

class GenerateSlip implements OrderFinalizationInterface
{
	protected string $path = '';

	protected string $barCodePrePend = 'barkod';
	protected string $uplatnicaBezBarCodePrePend = 'uplatnica-bez-barcode';
	protected string $uplatnicaSaBarCodePrePend = 'uplatnica-sa-barcode';

	public function __construct(private BarCodeGenerator $barCodeGenerator, private PaymentSlipGenerator $paymentSlipGenerator)
	{
		$this->path = storage_path('app');
	}

	public function finalize(Order $order): void
	{
		$customerName = $order->getCustomerName();

		$this->barCodeGenerator->generate($order->final_price, $order->reference_number, $customerName, $this->path);

		$path = $this->path . "/barkod-$order->reference_number.png";

		$this->paymentSlipGenerator->generate($order->final_price, $customerName, $order->reference_number)
									->applyBarCode($order->reference_number, $path);

		$this->updateOrder($order);

		$this->cleanup($order->reference_number);

		$this->addMediaToOrder($order);

		CreateOfferAndSendEmail::dispatch($order);
	}

	public function cleanup(string $referenceNumber): void
	{
		if (file_exists($file = "$this->path/$this->barCodePrePend-$referenceNumber.png")) {
			unlink($file);
		}

		if (file_exists($file = "$this->path/$this->uplatnicaBezBarCodePrePend-$referenceNumber.jpg")) {
			unlink($file);
		}
	}

	public function addMediaToOrder(Order $order): void
	{
		if (file_exists($file = "$this->path/$this->uplatnicaSaBarCodePrePend-$order->reference_number.jpg")) {
			$order->addMedia($file)->toMediaCollection('virman');
		}
	}

	public function updateOrder(Order $order): void
	{
		$order->update(['status' => $order::STATUS_OFFER_SENT]);
	}
}
