<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\View\View;
use App\Mail\FeedbackMail;
use App\Models\PushDevice;
use App\Models\Notification;
use App\Http\Requests\ChangeLocale;
use App\Http\Requests\StoreFeedback;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Route;
use Laravel\Octane\Swoole\WorkerState;
use App\Http\Requests\InvalidateSessions;

class MiscController extends Controller
{
	public function dashboard(): View
	{
		$users_count = User::count();
		$notifications_count = Notification::count();
		$devices_count = PushDevice::count();

		return view('dashboard', compact('users_count', 'notifications_count', 'devices_count'));
	}

	public function feedback(StoreFeedback $request): RedirectResponse
	{
		Mail::send(new FeedbackMail($request->user(), $request->message));

		return $this->redirectFromSave();
	}

	public function invalidateSessions(InvalidateSessions $request): RedirectResponse
	{
		try {
			auth()->logoutOtherDevices($request->current_password);
		} catch (Exception $e) {
			return $this->returnWithErrors([
				'current_password' => $e->getMessage()
			]);
		}

		return $this->redirectFromSave();
	}

	public function changeLocale(ChangeLocale $request): RedirectResponse
	{
		$request->user()->update(['locale' => $request->code]);

		return $this->redirectFromSave();
	}

	public function techinfo(): View
	{
		$octane_stats = class_exists(WorkerState::class) && app()->has(WorkerState::class) ? app(WorkerState::class)->server->stats() : null;

		try {
			$queue_size = Queue::size();
		} catch (Exception $e) {
			$queue_size = 0;
		}

		return view('tech-info', compact('queue_size', 'octane_stats'));
	}

	public function telescope(): RedirectResponse
	{
		return Route::has('telescope') ? redirect()->route('telescope') : back();
	}

	public function horizon(): RedirectResponse
	{
		return Route::has('horizon.index') ? redirect()->route('horizon.index') : back();
	}
}
