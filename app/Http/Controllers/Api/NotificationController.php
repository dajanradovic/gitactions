<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationController extends Controller
{
	public function index(): JsonResource
	{
		return NotificationResource::collection(Notification::includes()
			->available()
			->latest('scheduled_at')
			->simplePaginate());
	}

	public function single(Notification $id): JsonResource
	{
		if (!$id->isAvailable()) {
			abort(403);
		}

		$id->markAsSeen();

		return new NotificationResource($id);
	}

	public function remove(Notification $id): JsonResponse
	{
		if (!$id->isAvailable()) {
			abort(403);
		}

		return response()->json(['data' => [
			'deleted' => $id->userTarget->delete(),
		]]);
	}

	public function seenAll(): JsonResponse
	{
		auth()->user()->markAllNotificationsAsSeen();

		return response()->json(['data' => [
			'status' => true,
		]]);
	}
}
