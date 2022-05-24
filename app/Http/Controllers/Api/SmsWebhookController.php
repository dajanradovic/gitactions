<?php

namespace App\Http\Controllers\Api;

use App\Models\SmsMessage;
use Illuminate\Http\JsonResponse;
use App\Models\IncomingSmsMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\SMS\NthIncomingSms;
use App\Http\Requests\SMS\ElksIncomingSms;
use App\Http\Requests\SMS\NthDeliveryReport;
use App\Http\Requests\SMS\TwilioIncomingSms;
use App\Http\Requests\SMS\VonageIncomingSms;
use App\Http\Requests\SMS\ElksDeliveryReport;
use App\Http\Requests\SMS\InfoBipIncomingSms;
use App\Http\Requests\SMS\TwilioDeliveryReport;
use App\Http\Requests\SMS\VonageDeliveryReport;
use App\Http\Requests\SMS\InfoBipDeliveryReport;

class SmsWebhookController extends Controller
{
	public function vonageReport(SmsMessage $id, VonageDeliveryReport $request): JsonResponse
	{
		$id->update([
			'external_id' => $request->messageId,
			'from' => $request->msisdn,
			'to' => $request->to,
			'status' => $request->status,
			'error_code' => $request->{'err-code'}
		]);

		return response()->json();
	}

	public function vonageIncoming(VonageIncomingSms $request): JsonResponse
	{
		IncomingSmsMessage::create([
			'provider' => SmsMessage::PROVIDER_VONAGE,
			'external_id' => $request->messageId,
			'from' => $request->msisdn,
			'to' => $request->to,
			'body' => $request->text
		]);

		return response()->json();
	}

	public function twilioReport(SmsMessage $id, TwilioDeliveryReport $request): JsonResponse
	{
		$id->update([
			'external_id' => $request->MessageSid,
			'from' => $request->From,
			'to' => $request->To,
			'status' => $request->MessageStatus
		]);

		return response()->json();
	}

	public function twilioIncoming(TwilioIncomingSms $request): JsonResponse
	{
		IncomingSmsMessage::create([
			'provider' => SmsMessage::PROVIDER_TWILIO,
			'external_id' => $request->MessageSid,
			'from' => $request->From,
			'to' => $request->To,
			'body' => $request->Body
		]);

		return response()->json();
	}

	public function infobipReport(InfoBipDeliveryReport $request): JsonResponse
	{
		foreach ($request->results as $message) {
			$smsMessage = SmsMessage::find($message['messageId']);

			if (!$smsMessage) {
				continue;
			}

			$smsMessage->update([
				'external_id' => $message['messageId'],
				'to' => $message['to'],
				'message_count' => $message['smsCount'],
				'price' => $message['price']['pricePerMessage'],
				'price_currency' => $message['price']['currency'],
				'status' => $message['status']['groupName']
			]);
		}

		return response()->json();
	}

	public function infobipIncoming(InfoBipIncomingSms $request): JsonResponse
	{
		foreach ($request->results as $message) {
			IncomingSmsMessage::create([
				'provider' => SmsMessage::PROVIDER_INFOBIP,
				'external_id' => $message['messageId'],
				'from' => $message['from'],
				'to' => $message['to'],
				'body' => $message['text']
			]);
		}

		return response()->json();
	}

	public function nthReport(SmsMessage $id, NthDeliveryReport $request): JsonResponse
	{
		$id->update([
			'external_id' => $request->messageId,
			'status' => $request->statusText ?? $request->status['code']
		]);

		return response()->json();
	}

	public function nthIncoming(NthIncomingSms $request): JsonResponse
	{
		IncomingSmsMessage::create([
			'provider' => SmsMessage::PROVIDER_NTH,
			'external_id' => $request->messageId,
			'from' => $request->phoneNumber,
			'to' => $request->receiver,
			'body' => $request->text
		]);

		return response()->json();
	}

	public function elksReport(SmsMessage $id, ElksDeliveryReport $request): JsonResponse
	{
		$id->update([
			'external_id' => $request->id,
			'status' => $request->statusText
		]);

		return response()->json();
	}

	public function elksIncoming(ElksIncomingSms $request): JsonResponse
	{
		IncomingSmsMessage::create([
			'provider' => SmsMessage::PROVIDER_ELKS,
			'external_id' => $request->id,
			'from' => $request->from,
			'to' => $request->to,
			'body' => $request->message
		]);

		return response()->json();
	}
}
