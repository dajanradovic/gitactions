<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Requests\MultiValues;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class DbController extends Controller
{
	public function index(): View
	{
		$tables = DB::connection()->getDoctrineSchemaManager()->listTables();

		return view('db.list', compact('tables'));
	}

	public function show(string $table): View
	{
		$table = DB::connection()->getDoctrineSchemaManager()->listTableDetails($table);

		return view('db.show', compact('table'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		foreach ($request->values as $table) {
			Schema::dropIfExists($table);
		}

		return $this->redirectFromSave();
	}

	public function multiTruncate(MultiValues $request): RedirectResponse
	{
		foreach ($request->values as $table) {
			DB::table($table)->truncate();
		}

		return $this->redirectFromSave();
	}

	public function multiRemoveColumns(MultiValues $request, string $table): RedirectResponse
	{
		Schema::dropColumns($table, $request->values);

		return $this->redirectFromSave();
	}
}
