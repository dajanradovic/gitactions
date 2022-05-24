<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Notification;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DateRangeSearch;
use App\Http\Requests\StoreNotification;

class NotificationController extends Controller
{
	public function cancel(Notification $id): RedirectResponse
	{
		$id->cancel();

		return $this->redirectFromSave();
	}

	public function create(): View
	{
		return view('notifications.add');
	}

	public function destroy(Notification $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('notifications.list');
	}

	public function index(DateRangeSearch $request): View
	{
		$user = $request->user();

		$start_date = $request->start_date ?? formatTimestamp(now($user->timezone)->startOfMonth(), 'Y-m-d H:i');
		$end_date = $request->end_date ?? formatTimestamp(now($user->timezone)->endOfMonth(), 'Y-m-d H:i');

		$notifications = Notification::withCount('targets')->whereBetween('created_at', [$start_date, $end_date])->latest('scheduled_at')->get();

		return view('notifications.list', compact('notifications', 'start_date', 'end_date'));
	}

	public function multiCancel(MultiValues $request): RedirectResponse
	{
		$notifications = Notification::whereNotNull('scheduled_at')
			->where('canceled', false)
			->whereNotNull('external_id')
			->whereDate('scheduled_at', '>', formatTimestamp())
			->whereIn('id', $request->values)
			->get();

		foreach ($notifications as $notification) {
			$notification->cancel();
		}

		return $this->redirectFromSave();
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Notification::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function show(Notification $id): View
	{
		$notification = $id;

		return view('notifications.show', compact('notification'));
	}

	public function store(StoreNotification $request): RedirectResponse
	{
		$notification = Notification::create([
			'title' => $request->title,
			'body' => $request->body,
			'url' => $request->url,
			'countries' => $request->countries,
			'radius' => $request->radius,
			'location_lat' => $request->location_lat,
			'location_lng' => $request->location_lng,
			'scheduled_at' => formatTimestamp($request->scheduled_at, 'Y-m-d H:i:s', $request->user()->timezone),
		]);

		if (!$notification->url) {
			$notification->update(['url' => $notification->storage()->handle()->getFirst('file')]);
		}

		$targets = User::notifiable()->get();

		foreach ($targets as $target) {
			$target->notificationTargets()->create([
				'notification_id' => $notification->id,
			]);
		}

		$notification->send();

		return $this->redirectFromSave('notifications.list');
	}
}
