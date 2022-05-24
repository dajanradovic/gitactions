<?php

namespace App\Services\Support;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ErpHandler
{
	private ?string $apiKey;
	private ?string $username;
	private ?string $password;
	private ?string $baseUrl;

	public function __construct(?string $username = null, ?string $password = null, ?string $domain = null, ?string $apiKey = null)
	{
		$this->baseUrl = $domain ?? setting('erp_base_url');
		$this->apiKey = $apiKey ?? setting('erp_api_key');
		$this->username = $username ?? setting('erp_client_id');
		$this->password = $password ?? setting('erp_client_secret');
	}

	public function getProducts(): array
	{
		$data = Http::baseUrl($this->baseUrl)->withToken($this->getToken())->asJson()->get('webshop/getProducts/' . $this->apiKey);

		return $this->returnResponse($data);
	}

	public function getStock(): array
	{
		$data = Http::baseUrl($this->baseUrl)->withToken($this->getToken())->asJson()->get('webshop/getStock/' . $this->apiKey);

		return $this->returnResponse($data);
	}

	public function createOrder(Order $order): array
	{
		$orderData = $this->prepareCreateOrderBody($order);

		$data = Http::baseUrl($this->baseUrl)->withToken($this->getToken())->asJson()->post('webshop/order/' . $this->apiKey, $orderData);

		return $this->returnResponse($data);
	}

	protected function returnResponse(Response $data, ?array $default = null): ?array
	{
		return $data->successful() ? $data->json() : $default;
	}

	protected function prepareCreateOrderBody(Order $order): array
	{
		$orderItems = [];

		foreach ($order->orderItems as $index => $item) {
			$orderItems[] = [
				'OrderItemId' => $index + 1,
				'SKU' => $item->product->code,
				'Name' => $item->product->name,
				'Quantity' => $item->quantity,
				'PricePerItem' => $item->total_price,
				'ListPricePerItem' => $item->price,
				'TotalPrice' => $item->total_price_with_discounts,
				'TotalAdjustment' => $item->discounts
			];
		}

		return [
			'Order' => [
				'OrderId' => $order->order_number,
				'OrderPlacedTime' => formatTimestamp($order->created_at),
				'Customer' => [
					'ShippingAddress' => [
						'FirstName' => $order->delivery_address['name'],
						'Phone' => $order->delivery_address['phone'],
						'Address' => $order->delivery_address['street'],
						'City' => $order->delivery_address['town'],
						'ZipCode' => $order->delivery_address['zip_code'],
						'Country' => $order->delivery_address['country_code'],
						'Email' => $order->customer_email
					],
					'BillingAddress' => [
						'FirstName' => $order->invoice_address['name'],
						'Phone' => $order->invoice_address['phone'],
						'Address' => $order->invoice_address['street'],
						'City' => $order->invoice_address['town'],
						'ZipCode' => $order->invoice_address['zip_code'],
						'Country' => $order->invoice_address['country_code'],
						'Email' => $order->customer_email
					]
				],
				'OrderItems' => [
					'OrderItem' => $orderItems,
				],
				'ShippingMode' => $order->shipments()->first()->delivery_service,
				'ShippingCost' => $order->shipping_price,
				'UserNotes' => $order->delivery_address['note'],
				'PaymentMethods' => [
					'PaymentMethod' => [
						'Name' => '', // sifra nacina placanje - mora bit mapirana s nacinom placanja u virgi,
						'Amount' => $order->final_price
					]
				]
			]
		];
	}

	private function getToken(): string|null
	{
		$data = Http::baseUrl($this->baseUrl)->withBasicAuth($this->username, $this->password)->asForm()->post('oauth/token', [
			'grant_type' => 'client_credentials'
		]);

		if ($data->successful()) {
			return $data['access_token'];
		}

		return null;
	}
}
