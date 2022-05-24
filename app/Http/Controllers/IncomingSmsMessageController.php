<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use App\Models\IncomingSmsMessage;
use App\Http\Requests\GlobalSearch;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DateRangeSearch;

class IncomingSmsMessageController extends Controller
{
	public function destroy(IncomingSmsMessage $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('sms-messages.incoming.list');
	}

	public function index(DateRangeSearch $request): View
	{
		$user = $request->user();
		$provider = $request->provider;

		$start_date = $request->start_date ?? formatTimestamp(now($user->timezone)->startOfMonth(), 'Y-m-d H:i');
		$end_date = $request->end_date ?? formatTimestamp(now($user->timezone)->endOfMonth(), 'Y-m-d H:i');

		$sms_messages = IncomingSmsMessage::when($provider, function ($query) use ($provider): void {
			$query->where('provider', $provider);
		})
			->whereBetween('created_at', [$start_date, $end_date])
			->latest()
			->get();

		return view('sms-messages.incoming.list', compact('sms_messages', 'provider', 'start_date', 'end_date'));
	}

	public function search(GlobalSearch $request): View
	{
		$search = $request->search;
		$provider = $request->provider;

		$sms_messages = IncomingSmsMessage::when($provider, function ($query) use ($provider): void {
			$query->where('provider', $provider);
		})
			->search($search)
			->latest()
			->get();

		return view('sms-messages.incoming.list', compact('sms_messages', 'provider', 'search'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		IncomingSmsMessage::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function show(IncomingSmsMessage $id): View
	{
		$sms_message = $id;

		return view('sms-messages.incoming.show', compact('sms_message'));
	}
}
