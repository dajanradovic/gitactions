<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use App\Http\Requests\StoreBanner;
use Illuminate\Http\RedirectResponse;

class BannerController extends Controller
{
	public function create(): View
	{
		$banner = new Banner;

		return view('banners.add', compact('banner'));
	}

	public function destroy(Banner $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('banners.list');
	}

	public function edit(Banner $id): View
	{
		$banner = $id;

		return view('banners.add', compact('banner'));
	}

	public function index(): View
	{
		$banners = Banner::orderBy('order_column')->get();

		return view('banners.list', compact('banners'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Banner::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function store(StoreBanner $request): RedirectResponse
	{
		$banner = Banner::create([
			'type' => $request->type,
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'order_column' => $request->order_column,
			'url' => $request->url,
			'active' => $request->boolean('active'),
		]);

		$banner->storage()->handle($request->media);

		return $this->redirectFromSave('banners.list');
	}

	public function update(StoreBanner $request, Banner $id): RedirectResponse
	{
		$id->update([
			'type' => $request->type,
			'title' => $request->title,
			'subtitle' => $request->subtitle,
			'order_column' => $request->order_column,
			'url' => $request->url,
			'active' => $request->boolean('active'),
		]);

		$id->storage()->handle($request->media);

		return $this->redirectFromSave('banners.list');
	}
}
