<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigController extends Controller
{
	public function config(): JsonResource
	{
		return new ConfigResource(setting());
	}
}
