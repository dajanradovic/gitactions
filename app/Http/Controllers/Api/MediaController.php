<?php

namespace App\Http\Controllers\Api;

use App\Models\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProtectedMedia;
use Illuminate\Http\RedirectResponse;

class MediaController extends Controller
{
	public function getMediaUrl(Media $id, ProtectedMedia $request): RedirectResponse
	{
		$storage = $id->model->storage();
		$width = $request->query('width');
		$conversion = $request->query('conversion');

		$url = $width ? $storage->getResponsiveImage($id, $width, $conversion) : $storage->url($id, $conversion);

		return redirect()->away($url);
	}
}
