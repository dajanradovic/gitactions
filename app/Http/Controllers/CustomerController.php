<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use App\Http\Requests\StoreCustomer;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreAddressArray;

class CustomerController extends Controller
{
	public function index(): View
	{
		$users = Customer::with(['addresses', 'authParent' => function ($query): void {
			$query->with('role')->withCount('sessions');
		}])
			->has('authParent')
			->join('users', 'users.user_id', '=', 'customers.id')
			->orderBy('users.name')
			->select('customers.*')
			->get();

		return view('customers.list', compact('users'));
	}

	public function edit(Customer $id): View
	{
		$user = $id->authParent;
		$addresses = $id->addresses;

		$roles = Role::orderBy('name')->get();

		return view('customers.add', compact('user', 'roles', 'addresses'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Customer::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function destroy(Customer $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('users.list');
	}

	public function update(StoreCustomer $request, Customer $id): RedirectResponse
	{
		$addressValidator = new StoreAddressArray;

		$this->validate($request, $addressValidator->rules());

		$id->update([
			'surname' => $request->surname,
			'oib' => $request->oib,
			'date_of_birth' => $request->date_of_birth,
			'company_name' => $request->company_name,
			'newsletter' => $request->boolean('newsletter'),
			'club_card' => $request->boolean('club_card'),
		]);

		$id->addresses()->delete();

		foreach (Address::getTypes() as $type) {
			if (isset($request->address_name[$type], $request->country_code[$type], $request->street[$type], $request->city[$type], $request->zip_code[$type])) {
				$id->addresses()->create([
					'type' => $type,
					'name' => $request->address_name[$type],
					'country_code' => $request->country_code[$type],
					'street' => $request->street[$type],
					'city' => $request->city[$type],
					'zip_code' => $request->zip_code[$type],
					'phone' => $request->phone[$type]
				]);
			}
		}

		$id = $id->authParent;

		$this->validate($request, [
			'email' => ['required', 'max:50', 'email', 'unique:' . $id::class . ',email,' . $id->id],
		]);

		$id->update([
			'name' => $request->name,
			'email' => $request->email,
			'active' => $request->boolean('active'),
			'allow_push_notifications' => false,
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

		return $this->redirectFromSave('customers.list');
	}
}
