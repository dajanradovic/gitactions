<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected function redirectFromSave(?string $route = null, array $params = []): RedirectResponse
	{
		$redirect = null;
		$request = request();

		if ($request->has('save_and_return')) {
			$redirect = empty($request->save_and_return) ? back() : redirect($request->save_and_return);
		}

		$redirect = $redirect ?? (empty($route) ? back() : redirect()->route($route, $params));

		return $redirect->with('request_successful', __('global.request-successful'));
	}

	protected function returnWithErrors(array $errors = [], ?array $input = null): RedirectResponse
	{
		return back()->withInput($input)->withErrors($errors);
	}
}
