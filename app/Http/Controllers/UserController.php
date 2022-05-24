<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Session;
use Illuminate\View\View;
use App\Http\Requests\StoreUser;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
	public function create(): View
	{
		$user = new User;
		$roles = Role::orderBy('name')->get();

		return view('users.add', compact('user', 'roles'));
	}

	public function destroy(Admin $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('users.list');
	}

	public function edit(Admin $id): View
	{
		$user = $id->authParent;
		$roles = Role::orderBy('name')->get();

		return view('users.add', compact('user', 'roles'));
	}

	public function index(): View
	{
		$users = Admin::with(['authParent' => function ($query): void {
			$query->with('role')->withCount('sessions');
		}])
			->has('authParent')
			->join('users', 'users.user_id', '=', 'admins.id')
			->orderBy('users.name')
			->select('admins.*')
			->get();

		return view('users.list', compact('users'));
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		User::where('active', false)
			->whereHasMorph('user', '*', function ($query) use ($request): void {
				$query->whereIn('id', $request->values);
			})
			->update(['active' => true]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		User::where('active', true)
			->whereHasMorph('user', '*', function ($query) use ($request): void {
				$query->whereIn('id', $request->values);
			})
			->update(['active' => false]);

		return $this->redirectFromSave();
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Admin::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function profile(): View
	{
		$user = auth()->user();
		$roles = Role::orderBy('name')->get();

		return view('users.add', compact('user', 'roles'));
	}

	public function store(StoreUser $request): RedirectResponse
	{
		$this->validate($request, [
			'email' => ['required', 'max:50', 'email', 'unique:' . User::class],
		]);

		Admin::create()->authParent()->create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => $request->password,
			'active' => $request->boolean('active'),
			'allow_push_notifications' => $request->boolean('allow_push_notifications'),
			'timezone' => $request->timezone,
			'locale' => $request->locale,
			'role_id' => $request->role_id,
			'email_verified_at' => $request->boolean('verified') ? formatTimestamp() : null,
		]);

		return $this->redirectFromSave('users.list');
	}

	public function update(StoreUser $request, Admin $id): RedirectResponse
	{
		$id = $id->authParent;

		$this->validate($request, [
			'email' => ['required', 'max:50', 'email', 'unique:' . $id::class . ',email,' . $id->id],
		]);

		$id->update([
			'name' => $request->name,
			'email' => $request->email,
			'active' => $request->boolean('active'),
			'allow_push_notifications' => $request->boolean('allow_push_notifications'),
			'timezone' => $request->timezone,
			'locale' => $request->locale,
			'role_id' => $request->role_id,
			'email_verified_at' => $request->boolean('verified') ? formatTimestamp($id->email_verified_at) : null,
		]);

		if ($request->password) {
			$id->update([
				'password' => $request->password
			]);
		}

		$id->storage()->handle($request->media);

		return $this->redirectFromSave('users.list');
	}

	public function sessions(User $id): View
	{
		$user = $id;
		$sessions = $id->sessions()->orderBy('last_activity', 'desc')->get();

		return view('users.sessions', compact('user', 'sessions'));
	}

	public function removeSessions(MultiValues $request): RedirectResponse
	{
		Session::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function toggle2FAForm(User $id): View
	{
		$user = $id;

		return view('users.2fa', compact('user'));
	}
}
