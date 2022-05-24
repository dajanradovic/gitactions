<?php

namespace App\Services\SMS;

use App\Services\Support\BaseApiHandler;

class InfoBipHandler extends BaseApiHandler
{
	public function __construct(?string $username = null, ?string $password = null, ?string $apiSubdomain = null)
	{
		parent::__construct('https://' . ($apiSubdomain ?? setting('infobip_api_subdomain')) . '.api.infobip.com/');

		$this->client->withBasicAuth($username ?? setting('infobip_username'), $password ?? setting('infobip_password'));
	}

	public function setApiKey(string $apiKey): self
	{
		$this->client = $this->client->withToken($apiKey, 'App');

		return $this;
	}

	public function createApiKey(array $data): ?array
	{
		$data = $this->client->post('settings/1/accounts/_/api-keys', $data);

		return $this->returnResponse($data);
	}

	public function updateApiKey(string $id, array $data): ?array
	{
		$data = $this->client->put('settings/1/accounts/_/api-keys/' . $id, $data);

		return $this->returnResponse($data);
	}

	public function createScenario(array $data): ?array
	{
		$data = $this->client->post('omni/1/scenarios', $data);

		return $this->returnResponse($data);
	}

	public function updateScenario(string $id, array $data): ?array
	{
		$data = $this->client->put('omni/1/scenarios/' . $id, $data);

		return $this->returnResponse($data);
	}

	public function createMessage(array $data): ?array
	{
		$data = $this->client->post('omni/1/advanced', $data);

		return $this->returnResponse($data);
	}

	public function sms(array $data): ?array
	{
		$data = $this->client->post('sms/2/text/advanced', $data);

		return $this->returnResponse($data);
	}

	public function getWhatsAppTemplates(string $sender): ?array
	{
		$data = $this->client->get('whatsapp/1/senders/' . $sender . '/templates');

		return $this->returnResponse($data);
	}
}
