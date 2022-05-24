<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Location\GoogleMapGeocoder;
use App\Http\Requests\Location\GeocodeByLatLng;
use App\Http\Requests\Location\SearchCountries;
use App\Services\Location\RestCountriesHandler;
use App\Http\Requests\Location\GeocodeByAddress;

class LocationController extends Controller
{
	public function countries(SearchCountries $request): JsonResponse
	{
		$restCountries = new RestCountriesHandler;

		return response()->json([
			'data' => $restCountries->all($request->fields) ?? []
		]);
	}

	public function address(GeocodeByAddress $request): JsonResponse
	{
		$geocoder = new GoogleMapGeocoder;

		return response()->json([
			'data' => $geocoder->getLocationByAddress($request->address)
		]);
	}

	public function latLng(GeocodeByLatLng $request): JsonResponse
	{
		$geocoder = new GoogleMapGeocoder;

		return response()->json([
			'data' => $geocoder->getAddressByLocation($request->lat, $request->lng)
		]);
	}
}
