<?php

namespace App\Http\Controllers;

use App\Models\Product;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use App\Http\Requests\MultiValues;
use App\Http\Requests\StoreDiscount;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreCouponCodeDiscount;
use App\Http\Requests\StoreAboveSetPriceDiscount;

class DiscountController extends Controller
{
	public function index(): View
	{
		$discounts = Discount::latest()->get();

		return view('discounts.list', compact('discounts'));
	}

	public function create(): View
	{
		$discount = new Discount;

		$products = Product::get(['id', 'name']);
		$categories = Category::get(['id', 'name']);

		return view('discounts.add', compact('discount', 'products', 'categories'));
	}

	public function store(StoreDiscount $request): RedirectResponse
	{
		$discount = Discount::create([
			'title' => $request->title,
			'period_from' => $request->period_from ? formatTimestamp($request->period_from, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'period_to' => $request->period_to ? formatTimestamp($request->period_to, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'amount' => $request->amount,
			'is_percentage' => $request->boolean('is_percentage'),
			'active' => $request->boolean('active'),
			'add_up_with_other_discounts' => $request->boolean('add_up_with_other_discounts'),
			'type' => Discount::GENERAL_DISCOUNT
		]);

		return $this->redirectFromSave('discounts.list');
	}

	public function edit(Discount $id): View
	{
		$discount = $id->load('items.product');

		$products = Product::get(['id', 'name']);
		$categories = Category::get(['id', 'name']);

		return view('discounts.add', compact('discount', 'products', 'categories'));
	}

	public function update(StoreDiscount $request, Discount $id): RedirectResponse
	{
		$id->update([
			'title' => $request->title,
			'period_from' => $request->period_from ? formatTimestamp($request->period_from, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'period_to' => $request->period_to ? formatTimestamp($request->period_to, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'amount' => $request->amount,
			'is_percentage' => $request->boolean('is_percentage'),
			'active' => $request->boolean('active'),
			'add_up_with_other_discounts' => $request->boolean('add_up_with_other_discounts'),
		]);

		$id->storeDiscountItems();

		return $this->redirectFromSave('discounts.list');
	}

	public function destroy(Discount $id): RedirectResponse
	{
		$id->delete();

		return $this->redirectFromSave('discount.list');
	}

	public function multiActivate(MultiValues $request): RedirectResponse
	{
		Discount::where('active', 0)->whereIn('id', $request->values)->update(['active' => 1]);

		return $this->redirectFromSave();
	}

	public function multiDeactivate(MultiValues $request): RedirectResponse
	{
		Discount::where('active', 1)->whereIn('id', $request->values)->update(['active' => 0]);

		return $this->redirectFromSave();
	}

	public function createCoupons(): View
	{
		$discount = new Discount;

		$products = Product::get(['id', 'name']);
		$categories = Category::get(['id', 'name']);

		return view('discounts.add-coupons', compact('discount', 'products', 'categories'));
	}

	public function storeCoupons(StoreCouponCodeDiscount $request): RedirectResponse
	{
		$request->validate([
			'code' => ['required', 'max:10', 'string', 'unique:' . Discount::class],
		]);

		$discount = Discount::create([
			'title' => $request->title,
			'max_use' => $request->max_use,
			'period_from' => $request->period_from ? formatTimestamp($request->period_from, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'period_to' => $request->period_to ? formatTimestamp($request->period_to, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'code' => $request->code,
			'amount' => $request->amount,
			'is_percentage' => $request->boolean('is_percentage'),
			'active' => $request->boolean('active'),
			'type' => $request->type
		]);

		return $this->redirectFromSave('discounts.list');
	}

	public function updateCoupons(StoreCouponCodeDiscount $request, Discount $id): RedirectResponse
	{
		$request->validate([
			'code' => ['required', 'max:10', 'string', Rule::unique('discounts')->ignore($id->id)],
		]);

		$id->update([
			'title' => $request->title,
			'max_use' => $request->max_use,
			'period_from' => $request->period_from ? formatTimestamp($request->period_from, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'period_to' => $request->period_to ? formatTimestamp($request->period_to, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'code' => $request->code,
			'amount' => $request->amount,
			'is_percentage' => $request->boolean('is_percentage'),
			'active' => $request->boolean('active'),
			'type' => $request->type
		]);

		$id->storeDiscountItems();

		return $this->redirectFromSave('discounts.list');
	}

	public function editCoupons(Discount $id): View
	{
		$discount = $id->load('items.product');

		$products = Product::get(['id', 'name']);

		$categories = Category::get(['id', 'name']);

		return view('discounts.add-coupons', compact('discount', 'products', 'categories'));
	}

	public function multiRemove(MultiValues $request): RedirectResponse
	{
		Discount::destroy($request->values);

		return $this->redirectFromSave();
	}

	public function updateAboveSetPriceDiscount(StoreAboveSetPriceDiscount $request, Discount $id): RedirectResponse
	{
		$id->update([
			'title' => $request->title,
			'max_use' => $request->max_use ?? 0,
			'period_from' => $request->period_from ? formatTimestamp($request->period_from, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'period_to' => $request->period_to ? formatTimestamp($request->period_to, 'Y-m-d H:i:s', $request->user()->timezone) : null,
			'code' => $request->code,
			'amount' => $request->amount,
			'is_percentage' => $request->boolean('is_percentage'),
			'active' => $request->boolean('active'),
			'type' => Discount::ABOVE_SET_PRICE_DISCOUNT
		]);

		$id->storeDiscountItems();

		return $this->redirectFromSave('discounts.list');
	}

	public function editAboveSetPriceDiscount(Discount $id): View
	{
		$discount = $id;

		return view('discounts.add-above-set-price', compact('discount'));
	}
}
