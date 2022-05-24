<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\SmsMessage;
use App\Http\Requests\MultiValues;
use App\Http\Requests\GlobalSearch;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DateRangeSearch;
use App\Http\Requests\StoreSmsMessage;

class SmsMessageController extends Controller
{
	public function create(): View
	{
		return view('sms-messages.add');
	}

	public function destroy(SmsMessage $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('sms-messages.list');
	}

	public function index(DateRangeSearch $request): View
	{
		$user = $request->user();
		$provider = $request->provider;

		$start_date = $request->start_date ?? formatTimestamp(now($user->timezone)->startOfMonth(), 'Y-m-d H:i');
		$end_date = $request->end_date ?? formatTimestamp(now($user->timezone)->endOfMonth(), 'Y-m-d H:i');

		$sms_messages = SmsMessage::when($provider, function ($query) use ($provider): void {
			$query->where('provider', $provider);
		})
			->whereBetween('created_at', [$start_date, $end_date])
			->latest()
			->get();

		return view('sms-messages.list', compact('sms_messages', 'provider', 'start_date', 'end_date'));
	}

	public function search(GlobalSearch $request): View
	{
		$search = $request->search;
		$provider = $request->provider;

		$sms_messages = SmsMessage::when($provider, function ($query) use ($provider): void {
			$query->where('provider', $provider);
		})
			->search($search)
			->latest()
			->get();

		return view('sms-messages.list', compact('sms_messages', 'provider', 'search'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		SmsMessage::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function show(SmsMessage $id): View
	{
		$sms_message = $id;

		return view('sms-messages.show', compact('sms_message'));
	}

	public function store(StoreSmsMessage $request): RedirectResponse
	{
		SmsMessage::create([
			'provider' => $request->provider,
			'from' => $request->from,
			'to' => $request->to,
			'body' => $request->body,
		]);

		return $this->redirectFromSave('sms-messages.list');
	}
}
