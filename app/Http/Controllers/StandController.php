<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stand;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StandController extends Controller
{
    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stands = Stand::with('gypsumProduct.product')
            ->orderBy('created_at', 'desc')
            ->paginate(11);

        return view('products.stands.index', compact('stands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.stands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::transaction(function () use ($request) {
           $product = Product::create($request->only('price', 'cost', 'in_stock', 'description'));
            if($request->hasFile('photo')){
                $this->handlePhotos($product, $request);
            }

           $gypsum_products = $product->gypsumProduct()->create(['type' => 'stand'])
               ->stand()
               ->create($request->only('stand_type', 'color', 'gypsum_weight'));
        });

        return redirect()->route('products.stands.index')->with(['success'=>'Подставка успешно создана']);
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
        $stand = Stand::findorfail($id);
        return view('products.stands.edit', compact('stand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $stand = Stand::findOrFail($id);

        DB::transaction(function () use ($request, $stand) {
            if($request->hasFile('photo')){
                $this->updatePhoto($stand->gypsumProduct->product, $request->file('photo'));
            }
           $stand->gypsumProduct->product->update($request->only('price', 'cost', 'in_stock', 'description'));
           $stand->update($request->only('stand_type', 'color', 'gypsum_weight'));
        });
        return redirect()->route('products.stands.index')->with(['success'=>'Успешно изменено']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
