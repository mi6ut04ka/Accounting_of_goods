<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'time_of_sale' => 'nullable|date',
            'product_name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $response = DB::transaction(function () use ($validated) {
            if (!empty($validated['product_id'])) {
                $product = Product::findOrFail($validated['product_id']);
                $quantity = $validated['quantity'];

                if ($product->set) {
                    foreach ($product->set->items as $setItem) {
                        if ($setItem->product_id && $setItem->product->in_stock < $setItem->quantity * $quantity) {
                            return redirect()->back()->with('error', "Недостаточно товара '{$setItem->product->name}' для продажи набора '{$product->name}'.");

                        }
                    }

                    foreach ($product->set->items as $setItem) {
                        if ($setItem->product_id) {
                            $setItem->product->decrementStock($setItem->quantity * $quantity);
                        }
                    }
                    $product->decrementStock($quantity);
                } else {

                    if ($product->in_stock < $quantity) {
                        return redirect()->back()->with('error', "Недостаточно товара '{$product->name}' на складе.");
                    }
                    $product->decrementStock($quantity);
                }

                DB::table('sales')->insert([
                    'price' => $product->price,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'time_of_sale' => $validated['time_of_sale'] ?: now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('sales')->insert([
                    'product_name' => $validated['product_name'],
                    'price' => $validated['price'],
                    'quantity' => $validated['quantity'],
                    'time_of_sale' => $validated['time_of_sale'] ?: now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Продажа добавлена успешно!');
        });

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($saleId)
    {
        DB::beginTransaction();

        try {
            $sale = DB::table('sales')->where('id', $saleId)->first();

            if (!$sale) {
                return redirect()->back()->with('error', 'Продажа не найдена.');
            }
            if (!is_null($sale->product_id)) {
                $product = Product::find($sale->product_id);
                $quantity = $sale->quantity;


                if ($product->set) {
                    foreach ($product->set->items as $setItem) {
                        if ($setItem->product_id) {
                            $setItem->product->incrementStock($setItem->quantity * $quantity);
                        }
                    }
                    $product->incrementStock($quantity);
                } else {
                    if ($product) {
                        $product->incrementStock($quantity);
                    }
                }
            }

            DB::table('sales')->where('id', $saleId)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Продажа успешно удалена.');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Ошибка при удалении продажи: " . $e->getMessage());

            return redirect()->back()->with('error', 'Произошла ошибка при удалении продажи.');
        }
    }
}
