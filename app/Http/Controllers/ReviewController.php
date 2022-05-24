<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
	public function index(): View
	{
		$reviews = Review::latest()->get();

		return view('reviews.list', compact('reviews'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Review::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function destroy(Review $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('reviews.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		Review::where('active', 0)->whereIn('id', $request->values)->update(['active' => 1]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		Review::where('active', 1)->whereIn('id', $request->values)->update(['active' => 0]);

		return $this->redirectFromSave();
	}
}
