<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;
use App\Http\Requests\StoreRole;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AssignRoleToUsers;

class RoleController extends Controller
{
	public function create(): View
	{
		$role = new Role;

		return view('roles.add', compact('role'));
	}

	public function destroy(Role $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('roles.list');
	}

	public function edit(Role $id): View
	{
		$role = $id;

		return view('roles.add', compact('role'));
	}

	public function index(): View
	{
		$roles = Role::withCount('users')->orderBy('name')->get();

		return view('roles.list', compact('roles'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Role::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function store(StoreRole $request): RedirectResponse
	{
		$role = Role::create([
			'name' => $request->name,
			'protected' => $request->boolean('protected'),
			'mode' => $request->mode,
			'api_rate_limit' => $request->api_rate_limit,
			'api_rate_limit_backoff_minutes' => $request->api_rate_limit_backoff_minutes,
			'disallowed_methods' => $request->disallowed_methods,
		]);

		$routes = $request->routes ?? [];

		foreach ($routes as $route) {
			$role->routes()->create([
				'route' => $route,
			]);
		}

		return $this->redirectFromSave('roles.list');
	}

	public function update(StoreRole $request, Role $id): RedirectResponse
	{
		$id->update([
			'name' => $request->name,
			'protected' => $request->boolean('protected'),
			'mode' => $request->mode,
			'api_rate_limit' => $request->api_rate_limit,
			'api_rate_limit_backoff_minutes' => $request->api_rate_limit_backoff_minutes,
			'disallowed_methods' => $request->disallowed_methods,
		]);

		$id->routes()->delete();
		$routes = $request->routes ?? [];

		foreach ($routes as $route) {
			$id->routes()->create([
				'route' => $route,
			]);
		}

		return $this->redirectFromSave('roles.list');
	}

	public function storeUsers(AssignRoleToUsers $request): RedirectResponse
	{
		User::whereHas('user', function ($query) use ($request): void {
			$query->whereIn('id', $request->values);
		})
		->update(['role_id' => $request->role_id]);

		return $this->redirectFromSave();
	}
}
