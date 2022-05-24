<?php

namespace App\Services\Orders\Payments;

use App\Models\Order;
use Illuminate\Support\Str;
use App\Contracts\OrderFinalizationInterface;

class CorvusPaymentService implements OrderFinalizationInterface
{
	private ?string $version;
	private ?string $store_id;
	private ?string $language;
	//private ?string $currency;
	private ?string $secret_key;
	private ?string $require_complete;

	public function __construct(?string $version = null, ?string $store_id = null, ?string $language = null, /*?string $currency = null,*/ ?string $secret_key = null, ?string $require_complete = null)
	{
		$this->version = $version ?? setting('corvus_version');
		$this->store_id = $store_id ?? setting('corvus_store_id');
		$this->language = $language ?? setting('corvus_language');
		//$this->currency = $currency ?? setting('corvus_currency');
		$this->secret_key = $secret_key ?? setting('corvus_secret_key');
		$this->require_complete = $require_complete ?? setting('corvus_require_complete') ? 'true' : 'false';
	}

	public function finalize(Order $order): void
	{
		$orderNumber = Str::uuid();

		$cart = $this->prepareCart($order);
		$hash = $this->calculateFormSignature($orderNumber, $cart, $order->final_price, $order->currency);

		$this->appendToOrder($order, $orderNumber, $cart, $hash);
	}

	public function calculateFormSignature(string $orderNumber, string $cart, string $amount, string $currency): string|false
	{
		$corvusParameters = [
			'amount' . $amount,
			'cart' . $cart,
			'currency' . $currency,
			'language' . $this->language,
			'order_number' . $orderNumber,
			'require_complete' . $this->require_complete,
			'store_id' . $this->store_id,
			'version' . $this->version,
		];

		// parameters must be in alphabetical order
		sort($corvusParameters);
		$sortedString = implode('', $corvusParameters);

		return hash_hmac('sha256', $sortedString, $this->secret_key);
	}

	public function calculateWebhookSignature(string $orderNumber, string $language, string $approvalCode): string|false
	{
		$corvusParameters = [
			'approval_code' . $approvalCode,
			'language' . $language,
			'order_number' . $orderNumber,
		];

		// parameters must be in alphabetical order
		sort($corvusParameters);
		$sortedString = implode('', $corvusParameters);

		return hash_hmac('sha256', $sortedString, $this->secret_key);
	}

	public function prepareCart(Order $order): string
	{
		$cart = '';

		$items = $order->orderItems;

		foreach ($items as $item) {
			$cart .= str_replace(' ', '', $item->product->name) . 'x' . $item->quantity;
		}

		return $cart;
	}

	public function appendToOrder(Order $order, string $orderNumber, string $cart, string $corvusSignature): void
	{
		$order->update(['payment_id' => $orderNumber]);

		$order->corvus = [
			'corvus_order_number' => $orderNumber,
			'cart' => $cart,
			'total_price' => $order->final_price,
			'corvus_signature' => $corvusSignature,
		];
	}
}
