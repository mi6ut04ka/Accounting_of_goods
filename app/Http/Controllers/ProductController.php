<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{

    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $products = Product::all()->filter(function ($product) use ($query) {
            return str_contains(mb_strtolower($product->name), $query) == true;
        })->take(10)
            ->values()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'in_stock' => $product->in_stock,
                ];
        });

        return response()->json($products);
    }

    public function updateStock(Request $request, $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'in_stock' => 'required|integer|min:0',
        ]);

        $product->in_stock = $validatedData['in_stock'];
        $product->save();

        return response()->json(['in_stock' => $product->in_stock]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $path = $request->input('type');

        return redirect()->route("products.{$path}.create");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findorfail($id);
        $product->set? $product->with('items'): '';
        return view('products.show', compact('product'));
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
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->back()->with('success', 'Продукт успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findorfail($id);

        $this->deletePhotos($product);

        $product->delete();

        return redirect()->route('products.molded_candles.index')->with('success', 'Продукт успешно удален');
    }
}
