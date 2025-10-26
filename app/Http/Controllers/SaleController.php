<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'time_of_sale' => 'nullable|date',
            'product_name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'aroma_id' => 'nullable|integer|exists:aromas,id',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $response = DB::transaction(function () use ($validated) {
            if (!empty($validated['product_id'])) {
                $product = Product::with(['category', 'setItems.containedProduct'])->findOrFail($validated['product_id']);
                $quantity = $validated['quantity'];
                $productName = $product->name;

                if ($product->category->is_set) {
                    foreach ($product->setItems as $setItem) {
                        if ($setItem->contained_product_id && $setItem->containedProduct->in_stock < $setItem->quantity * $quantity) {
                            return redirect()->back()->with('error', "Недостаточно товара '{$setItem->containedProduct->name}' для продажи набора '{$product->name}'.");
                        }
                    }

                    foreach ($product->setItems as $setItem) {
                        if ($setItem->contained_product_id) {
                            $setItem->containedProduct->decrementStock($setItem->quantity * $quantity);
                        }
                    }
                    $product->decrementStock($quantity);
                } else {
                    if (!empty($validated['aroma_id'])) {
                        $aromaId = $validated['aroma_id'];
                        $aroma = $product->aromas()->where('aroma_id', $aromaId)->first();
                        if (!$aroma) {
                            return redirect()->back()->with('error', 'Выбранный аромат не привязан к продукту.');
                        }
                        if ($aroma->pivot->in_stock < $quantity) {
                            return redirect()->back()->with('error', "Недостаточно аромата '{$aroma->name}' у продукта '{$product->name}' на складе.");
                        }
                        $product->aromas()->updateExistingPivot($aromaId, [
                            'in_stock' => $aroma->pivot->in_stock - $quantity,
                        ]);
                        $productName = $product->name . ', аромат «' . $aroma->name . '»';
                    } else {
                        if ($product->in_stock < $quantity) {
                            return redirect()->back()->with('error', "Недостаточно товара '{$product->name}' на складе.");
                        }

                        $product->decrementStock($quantity);
                        $productName = $product->name;
                    }
                }
                DB::table('sales')->insert([
                    'price' => $product->price,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'product_name'=> $productName,
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

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($saleId)
    {
        DB::beginTransaction();

        try {
            $sale = DB::table('sales')->where('id', $saleId)->first();

            if (!$sale) {
                return redirect()->back()->with('error', 'Продажа не найдена.');
            }

            if (!is_null($sale->product_id)) {
                $product = Product::with(['category', 'setItems.containedProduct', 'aromas'])->find($sale->product_id);
                $quantity = $sale->quantity;

                if ($product->category->is_set) {
                    foreach ($product->setItems as $setItem) {
                        if ($setItem->contained_product_id) {
                            $setItem->containedProduct->incrementStock($setItem->quantity * $quantity);
                        }
                    }
                    $product->incrementStock($quantity);
                } else {
                    if (str_contains($sale->product_name, 'аромат «')) {
                        preg_match('/аромат «(.*?)»/', $sale->product_name, $matches);
                        if (!empty($matches[1])) {
                            $aromaName = $matches[1];
                            $aroma = $product->aromas->firstWhere('name', $aromaName);

                            if ($aroma) {
                                $product->aromas()->updateExistingPivot($aroma->id, [
                                    'in_stock' => $aroma->pivot->in_stock + $quantity
                                ]);
                            }
                        }
                    } else {
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
