<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\CheeseClub;
use App\Http\Requests\MultiValues;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreCheeseClub;
use App\Http\Requests\ImportCheeseClubMembers;
use App\Services\ImportersAndHandlers\CheeseClubMemberImportHandler;

class CheeseClubController extends Controller
{
	public function index(): View
	{
		$cheese_clubs = CheeseClub::with(['customer'])->get();

		return view('cheese-club.list', compact('cheese_clubs'));
	}

	public function create(): View
	{
		$cheese_club = new CheeseClub;
		$cheese_club_types = CheeseClub::getTypes();

		return view('cheese-club.add', compact('cheese_club', 'cheese_club_types'));
	}

	public function edit(CheeseClub $id): View
	{
		$cheese_club = $id;
		$cheese_club_types = CheeseClub::getTypes();

		return view('cheese-club.add', compact('cheese_club', 'cheese_club_types'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		CheeseClub::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function destroy(CheeseClub $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('cheese-club.list');
	}

	public function store(StoreCheeseClub $request): RedirectResponse
	{
		$this->validate($request, [
			'email' => ['required', 'max:50', 'email', 'unique:' . CheeseClub::class],
		]);

		CheeseClub::create([
			'name' => $request->name,
			'surname' => $request->surname,
			'email' => $request->email,
			'date_of_birth' => $request->date_of_birth,
			'points' => $request->points,
			'club_type' => $request->club_type,
			'card_number' => $request->card_number
		]);

		return $this->redirectFromSave('cheese-club.list');
	}

	public function update(StoreCheeseClub $request, CheeseClub $id): RedirectResponse
	{
		$this->validate($request, [
			'email' => ['required', 'max:50', 'email', 'unique:' . $id::class . ',email,' . $id->id],
		]);

		$id->update([
			'name' => $request->name,
			'surname' => $request->surname,
			'email' => $request->email,
			'date_of_birth' => $request->date_of_birth,
			'points' => $request->points,
			'club_type' => $request->club_type,
			'card_number' => $request->card_number
		]);

		return $this->redirectFromSave('cheese-club.list');
	}

	public function createImport(): View
	{
		return view('cheese-club.import');
	}

	public function import(ImportCheeseClubMembers $request, CheeseClubMemberImportHandler $cheeseClubImportHandler): RedirectResponse
	{
		$cheeseClubImportHandler->setInitialVales($request->boolean('header_row'), 'file')->start();

		return $this->redirectFromSave('cheese-club.list');
	}
}
