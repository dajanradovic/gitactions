<?php

namespace App\Services\Orders\Payments;

use Exception;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use App\Services\Support\BaseApiHandler;
use Illuminate\Http\Client\PendingRequest;
use App\Contracts\OrderFinalizationInterface;

class PayPalPaymentService extends BaseApiHandler implements OrderFinalizationInterface
{
	protected string $clientId;
	protected string $clientSecret;
	protected ?string $token = null;

	public function __construct(?bool $sandbox = null)
	{
		$sandbox ??= setting('paypal_sandbox');

		$this->clientId = $sandbox ? setting('paypal_sandbox_client_id') : setting('paypal_client_id');
		$this->clientSecret = $sandbox ? setting('paypal_sandbox_client_secret') : setting('paypal_client_secret');

		parent::__construct($sandbox ? 'https://api-m.sandbox.paypal.com/' : 'https://api-m.paypal.com/');
	}

	public function finalize(Order $order): void
	{
		$this->appendToOrder($order);
	}

	public function isOrderValid(string $payId, string $amount): bool
	{
		$order = $this->getOrderDetails($payId);

		return !empty($order) && ($order['status'] == 'APPROVED' || $order['status'] == 'COMPLETED') /*&& $order['purchase_units'][0]['amount']['value'] == $amount*/;
	}

	public function getOrderDetails(string $payId): ?array
	{
		$data = $this->client()->get('v2/checkout/orders/' . $payId);

		return $this->returnResponse($data);
	}

	protected function client(): PendingRequest
	{
		return $this->auth()->client->asJson()->withToken($this->token);
	}

	protected function auth(): self
	{
		if ($this->token) {
			return $this;
		}

		$data = $this->client->asForm()->withBasicAuth($this->clientId, $this->clientSecret)->post('v1/oauth2/token', ['grant_type' => 'client_credentials']);
		$data = $this->returnResponse($data);

		$this->token = $data['access_token'] ?? null;

		return $this;
	}

	protected function appendToOrder(Order $order, string $convertedAmount = null): void
	{
		$order->paypal = ['link' => route('orders.pay', $order->id)];
	}

	public function convertToEur(Order $order): string{

		if($order->currency == 'HRK'){

			$response = Http::asJson()->get('https://api.hnb.hr/tecajn/v2?valuta=EUR');

			if($response->successful()){

				try{
					return bcdiv($order->final_price, str_replace(',', '.', $response->json()[0]['srednji_tecaj']), 2);

				}catch(Exception){

					throw new Exception('Something went wrong', 500);

				}
			}

			throw new Exception('Something went wrong', 500);

		}

		return $order->final_price;
	}


}
