<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\HandlesProductPhotos;
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
