<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Set;
use App\Models\SetItem;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetController extends Controller
{
    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sets = Set::with(['items','product'])->orderBy('created_at', 'desc')->get();
        return view('products.sets.index', compact('sets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all()->filter(function ($product) {
            return !$product->set;
        });

        return view('products.sets.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::create([
                'price' => $request->input('price'),
                'in_stock' => $request->input('in_stock')?? 1,
                'cost' => 0,
                'description' => $request->input('description') ?? null,
            ]);

            $set = $product->set()->create([
                'name' => $request->input('name'),
            ]);

            if($request->hasFile('photo')){
                $this->handlePhotos($product, $request, 'sets');
            }
            $final_cost = 0;

            foreach ($request->input('items') as $item) {
                $productId = $item['product_id'] ?? null;
                $product_item = $productId ? Product::find($productId) : null;

                $name = $product_item? $product_item->name: $item['name'];
                $cost = $product_item? $product_item->cost : $item['cost']?? 0;
                $quantity = $item['quantity'];
                $final_cost += $product_item? $product_item->cost*$quantity : $item['cost']*$quantity?? 0;

                SetItem::create([
                    'set_id' => $set->id,
                    'product_id' => $productId,
                    'name' => $name,
                    'cost' => $cost,
                    'quantity' => $quantity,
                ]);
            }
            $product->update(['cost' => $final_cost]);

            DB::commit();
            return redirect()->route('products.sets.index')->with('success', 'Набор успешно создан!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Ошибка при создании набора: " . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при создании набора.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Set $set)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $set = Set::with('items')->findOrFail($id);
        $products = Product::all()->filter(function ($product) {
            return !$product->set;
        });

        return view('products.sets.edit', compact( 'set','products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $set = Set::findOrFail($id);
            $product = $set->product;
            $product->update([
                'price' => $request->input('price'),
                'in_stock' => $request->input('in_stock')?? 1,
                'cost' => 0,
                'description' => $request->input('description')?? null
            ]);
            $product->set()->update([
                'name' => $request->input('name'),
            ]);
            if($request->hasFile('photo')){
                $this->updatePhoto($product, $request->file('photo'), 'sets');
            }

            $product->set->items()->delete();

            $final_cost = 0;

            foreach ($request->input('items') as $item) {
                $productId = $item['product_id'] ?? null;
                $product_item = $productId ? Product::find($productId) : null;

                $name = $product_item? $product_item->name : $item['name'] ?? 'без имени';
                $cost = $product_item? $product_item->cost : $item['cost'];

                $quantity = $item['quantity'];
                $final_cost += $product_item? $product_item->cost*$quantity : $item['cost']*$quantity?? 0;
                SetItem::create([
                    'set_id' => $set->id,
                    'product_id' => $productId,
                    'name' => $name,
                    'cost' => $cost,
                    'quantity' => $quantity,
                ]);
            }
            $product->update(['cost' => $final_cost]);

            DB::commit();
            return redirect()->route('products.sets.index')->with('success', 'Набор успешно обновлен!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Ошибка при обновлении набора: " . $e->getMessage());
            return redirect()->back()->with('error', 'Произошла ошибка при обновлении набора.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $set = Set::findOrfail($id);

            $product = $set->product;

            $this->deletePhotos($product);

            $product->set->items()->delete();

            $product->delete();

            DB::commit();
            return redirect()->route('products.sets.index')->with('success', 'Набор успешно удалён.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Ошибка при удалении набора: ' . $e->getMessage());
            return redirect()->route('products.sets.index')->with('error', 'Произошла ошибка при удалении набора.');
        }
    }
}
