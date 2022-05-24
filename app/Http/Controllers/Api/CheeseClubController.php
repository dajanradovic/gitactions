<?php

namespace App\Http\Controllers\Api;

use App\Models\CheeseClub;
use App\Http\Controllers\Controller;
use App\Http\Resources\CheeseClubResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CheeseClubController extends Controller
{
	public function index(): JsonResource
	{
		return CheeseClubResource::collection(CheeseClub::includes()->available()->latest()->paginate());
	}

	public function single(CheeseClub $id): JsonResource
	{
		return new CheeseClubResource($id);
	}
}
