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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = DB::transaction(function () use ($request) {

            $product = Product::findOrFail($request->input('product_id'));

            if ($product->in_stock < $request->input('quantity')) {
                return redirect()->back()->with('error', 'Недостаточно товара на складе.');
            }

            DB::table('sales')->insert([
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity'),
                'time_of_sale' => $request->input('time_of_sale') ?: now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $product->decrementStock($request->input('quantity'));

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
            $product = Product::findOrFail($sale->product_id);

            $product->incrementStock($sale->quantity);

            DB::table('sales')->where('id', $saleId)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Продажа успешно удалена и товар возвращен на склад!');
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Ошибка при откате продажи: " . $e->getMessage());

            return redirect()->back()->with('error', 'Произошла ошибка при откате продажи.');
        }
    }
}
