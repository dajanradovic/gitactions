<?php

namespace App\Http\Controllers\Api;

use App\Models\PushDevice;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushDevice;
use App\Http\Resources\PushDeviceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PushDeviceController extends Controller
{
	public function index(): JsonResource
	{
		return PushDeviceResource::collection(auth()->user()->devices()->latest()->simplePaginate());
	}

	public function removeAll(): JsonResponse
	{
		foreach (auth()->user()->devices as $device) {
			$device->delete();
		}

		return response()->json(['data' => [
			'deleted' => true
		]]);
	}

	public function remove(PushDevice $id): JsonResponse
	{
		if ($id->user_id != auth()->user()->id) {
			abort(403);
		}

		return response()->json(['data' => [
			'deleted' => $id->delete()
		]]);
	}

	public function store(StorePushDevice $request): JsonResource
	{
		$user = $request->user();

		$devices = PushDevice::where([
			['device_id', $request->device_id],
			['user_id', '<>', $user->id],
		])->get();

		foreach ($devices as $device) {
			$device->delete();
		}

		$device = $user->devices()->firstOrCreate([
			'device_id' => $request->device_id,
		]);

		$device->touch();

		return new PushDeviceResource($device);
	}
}
