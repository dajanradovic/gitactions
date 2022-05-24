<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\UptimeMonitor;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUptimeMonitor;

class UptimeMonitorController extends Controller
{
	public function index(): View
	{
		$monitors = UptimeMonitor::latest()->get();

		return view('monitors.list', compact('monitors'));
	}

	public function create(): View
	{
		$monitor = new UptimeMonitor;

		return view('monitors.add', compact('monitor'));
	}

	public function store(StoreUptimeMonitor $request): RedirectResponse
	{
		UptimeMonitor::create([
			'url' => $request->url,
			'uptime_check_interval_in_minutes' => $request->uptime_check_interval_in_minutes,
			'uptime_check_method' => $request->uptime_check_method,
			'uptime_check_enabled' => $request->boolean('uptime_check_enabled'),
			'certificate_check_enabled' => $request->boolean('certificate_check_enabled')
		]);

		return $this->redirectFromSave('monitors.list');
	}

	public function edit(UptimeMonitor $id): View
	{
		$monitor = $id;

		return view('monitors.add', compact('monitor'));
	}

	public function update(StoreUptimeMonitor $request, UptimeMonitor $id): RedirectResponse
	{
		$id->update([
			'url' => $request->url,
			'uptime_check_interval_in_minutes' => $request->uptime_check_interval_in_minutes,
			'uptime_check_method' => $request->uptime_check_method,
			'uptime_check_enabled' => $request->boolean('uptime_check_enabled'),
			'certificate_check_enabled' => $request->boolean('certificate_check_enabled')
		]);

		return $this->redirectFromSave('monitors.list');
	}

	public function destroy(UptimeMonitor $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('monitors.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		UptimeMonitor::where('uptime_check_enabled', false)->whereIn('id', $request->values)->update(['uptime_check_enabled' => true]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		UptimeMonitor::where('uptime_check_enabled', true)->whereIn('id', $request->values)->update(['uptime_check_enabled' => false]);

		return $this->redirectFromSave();
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		UptimeMonitor::destroy($request->values);

		return $this->redirectFromSave();
	}
}
