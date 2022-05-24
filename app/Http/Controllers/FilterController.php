<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\View\View;
use App\Models\Translation;
use Illuminate\Validation\Rule;
use App\Http\Requests\MultiValues;
use App\Http\Requests\StoreFilter;
use Illuminate\Http\RedirectResponse;

class FilterController extends Controller
{
	public function create(): View
	{
		$filter = new Filter;

		return view('filters.add', compact('filter'));
	}

	public function destroy(Filter $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('filters.list');
	}

	public function edit(Filter $id): View
	{
		$filter = $id;

		return view('filters.add', compact('filter'));
	}

	public function index(): View
	{
		$filters = Filter::includes()->latest()->get();

		return view('filters.list', compact('filters'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Filter::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function store(StoreFilter $request): RedirectResponse
	{
		$request->validate([
			'name' => ['required', 'max:50', 'string', 'unique:' . Filter::class],
			'name_en' => [
				'nullable', 'string', 'max:50', Rule::unique(Translation::class, 'value')
					->where(function ($query) {
						return $query->where('item_type', Filter::class);
					})
			],
		]);

		$filter = Filter::create([
			'name' => $request->name,
			'display_label' => $request->display_label,
			'type' => $request->type,
			'min' => $request->min,
			'max' => $request->max,
			'step' => $request->step,
			'value' => $request->value,
			'message' => $request->message,
			'required' => $request->boolean('required'),
			'searchable' => $request->boolean('searchable'),
			'active' => $request->boolean('active'),
		]);

		$filter->updateTranslations([
			'name' => [
				'en' => $request->name_en
			],
			'display_label' => [
				'en' => $request->display_label_en
			],
			'value' => [
				'en' => $filter->trimSelectValues($request->value_en)
			],
			'message' => [
				'en' => $request->message_en
			]
		]);

		return $this->redirectFromSave('filters.list');
	}

	public function update(StoreFilter $request, Filter $id): RedirectResponse
	{
		$request->validate([
			'name' => ['required', 'max:50', 'string', 'unique:' . get_class($id) . ',name,' . $id->id],
			'name_en' => [
				'required', 'string', 'max:50',
				Rule::unique(Translation::class, 'value')
					->ignore($id->id, 'item_id')
					->where(function ($query) use ($id) {
						return $query->where('item_type', get_class($id));
					})
			],
		]);

		$id->update([
			'name' => $request->name,
			'display_label' => $request->display_label,
			'type' => $request->type,
			'min' => $request->min,
			'max' => $request->max,
			'step' => $request->step,
			'value' => $request->value,
			'message' => $request->message,
			'required' => $request->boolean('required'),
			'searchable' => $request->boolean('searchable'),
			'active' => $request->boolean('active'),
		]);

		$id->updateTranslations([
			'name' => [
				'en' => $request->name_en
			],
			'display_label' => [
				'en' => $request->display_label_en
			],
			'value' => [
				'en' => $id->trimSelectValues($request->value_en)
			],
			'message' => [
				'en' => $request->message_en
			]
		]);

		return $this->redirectFromSave('filters.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		Filter::where('active', 0)->whereIn('id', $request->values)->update(['active' => 1]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		Filter::where('active', 1)->whereIn('id', $request->values)->update(['active' => 0]);

		return $this->redirectFromSave();
	}
}
