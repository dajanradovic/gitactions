<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\View\View;
use App\Http\Requests\StoreBlog;
use App\Http\Requests\MultiValues;
use App\Http\Requests\GlobalSearch;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\DateRangeSearch;

class BlogController extends Controller
{
	public function create(): View
	{
		$blog = new Blog;

		return view('blogs.add', compact('blog'));
	}

	public function destroy(Blog $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('blogs.list');
	}

	public function edit(Blog $id): View
	{
		$blog = $id;

		return view('blogs.add', compact('blog'));
	}

	public function index(DateRangeSearch $request): View
	{
		$user = $request->user();

		$start_date = $request->start_date ?? formatTimestamp(now($user->timezone)->startOfMonth(), 'Y-m-d H:i');
		$end_date = $request->end_date ?? formatTimestamp(now($user->timezone)->endOfMonth(), 'Y-m-d H:i');

		$blogs = Blog::withCount('activities')->whereBetween('created_at', [$start_date, $end_date])->latest()->get();

		return view('blogs.list', compact('blogs', 'start_date', 'end_date'));
	}

	public function search(GlobalSearch $request): View
	{
		$search = $request->search;
		$blogs = Blog::withCount('activities')->search($search)->latest()->get();

		return view('blogs.list', compact('blogs', 'search'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Blog::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function store(StoreBlog $request): RedirectResponse
	{
		$this->validate($request, [
			'slug' => ['required', 'string', 'max:50', 'unique:' . Blog::class],
		]);

		$blog = Blog::create([
			'title' => $request->title,
			'slug' => $request->slug,
			'body' => $request->body,
			'published_at' => formatTimestamp($request->published_at, 'Y-m-d H:i:s', $request->user()->timezone),
		]);

		$blog->syncTagsByName($request->tags);
		$blog->storage()->handle();

		return $this->redirectFromSave('blogs.list');
	}

	public function update(StoreBlog $request, Blog $id): RedirectResponse
	{
		$this->validate($request, [
			'slug' => ['required', 'string', 'max:50', 'unique:' . $id::class . ',slug,' . $id->id],
		]);

		$id->update([
			'title' => $request->title,
			'slug' => $request->slug,
			'body' => $request->body,
			'published_at' => formatTimestamp($request->published_at, 'Y-m-d H:i:s', $request->user()->timezone),
		]);

		$id->syncTagsByName($request->tags);
		$id->storage()->handle($request->media);

		return $this->redirectFromSave('blogs.list');
	}
}
