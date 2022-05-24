<?php

namespace App\Http\Controllers\Api;

use App\Models\PushDevice;
use App\Models\Notification;
use App\Http\Requests\ChartData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ChartController extends Controller
{
	public function devices(ChartData $request): JsonResponse
	{
		$user = $request->user();
		$min_date = formatTimestamp($request->min_date, 'Y-m-d H:i', $user->timezone);
		$max_date = formatTimestamp($request->max_date, 'Y-m-d H:i', $user->timezone);

		$records = PushDevice::whereBetween('created_at', [$min_date, $max_date])
			->oldest()
			->get()
			->groupBy(function ($date) use ($request): string {
				return formatLocalTimestamp($date->created_at, $request->date_format);
			});

		$devices = [];

		foreach ($records as $key => $value) {
			$devices[] = ['date' => $key, 'value' => count($value)];
		}

		return response()->json(['data' => $devices]);
	}

	public function notifications(ChartData $request): JsonResponse
	{
		$user = $request->user();
		$min_date = formatTimestamp($request->min_date, 'Y-m-d H:i', $user->timezone);
		$max_date = formatTimestamp($request->max_date, 'Y-m-d H:i', $user->timezone);

		$records = Notification::where('canceled', false)
			->whereNotNull('external_id')
			->whereBetween('scheduled_at', [$min_date, $max_date])
			->oldest('scheduled_at')
			->get()
			->groupBy(function ($date) use ($request): string {
				return formatLocalTimestamp($date->scheduled_at, $request->date_format);
			});

		$notifications = [];

		foreach ($records as $key => $value) {
			$notifications[] = ['date' => $key, 'value' => count($value)];
		}

		return response()->json(['data' => $notifications]);
	}
}
