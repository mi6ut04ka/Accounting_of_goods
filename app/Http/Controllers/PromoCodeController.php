<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::all();

        return view('promoCodes.index', compact('promoCodes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('promoCodes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:promo_codes,code|max:255',
            'discount' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable',
            'min_order_amount' => 'nullable|numeric|min:0',
            'applicable_categories.*' => 'string',
            'usage_limit' => 'nullable|integer|min:1',
        ]);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $categoriesJson = json_encode($validated['applicable_categories']);

        PromoCode::create([
            'code' => $validated['code'],
            'discount' => $validated['discount'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'],
            'min_order_amount' => $validated['min_order_amount'],
            'applicable_categories' => $categoriesJson,
            'usage_limit' => $validated['usage_limit'] ?? null,
        ]);

        return redirect()->route('promo-codes.index')->with('success', 'Промокод успешно добавлен!');
    }

    public function edit(PromoCode $promoCode)
    {
        $categories = Category::all();
        return view('promoCodes.edit', compact('promoCode', 'categories'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:promo_codes,code,' . $promoCode->id,
            'discount' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable',
            'min_order_amount' => 'nullable|numeric|min:0',
            'applicable_categories' => 'required|array',
            'applicable_categories.*' => 'string',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $categoriesJson = json_encode($validated['applicable_categories']);

        $promoCode->update([
            'code' => $validated['code'],
            'discount' => $validated['discount'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $validated['is_active'],
            'min_order_amount' => $validated['min_order_amount'] ?? 0,
            'applicable_categories' => $categoriesJson,
            'usage_limit' => $validated['usage_limit'] ?? null,
        ]);

        return redirect()->route('promo-codes.index')->with('success', 'Промокод успешно обновлен!');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('promo-codes.index')->with('success', 'Промокод успешно удален!');
    }
}
