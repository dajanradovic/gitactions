<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;

class StorageController extends Controller
{
	public function index(Request $request): View
	{
		$disk = $request->disk ?? '%';

		$disks = Media::select('disk')->groupBy('disk')->orderBy('disk')->get();
		$records = Media::selectRaw('model_type, sum(size) as files_size, count(*) as files_count')->where('disk', 'like', $disk)->groupBy('model_type')->orderBy('model_type')->get();

		return view('storage.list', compact('disks', 'records', 'disk'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		$records = Media::whereIn('model_type', $request->values)->get();

		foreach ($records as $record) {
			$record->delete();
		}

		return $this->redirectFromSave();
	}
}
