<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Statue;
use App\Models\Vase;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VaseController extends Controller
{
    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentPage = request()->get('page');

        $perPage = ($currentPage == 1 || $currentPage === null) ? 11 : 12;

        $vases = Vase::with('gypsumProduct.product')->paginate($perPage);
        return view('products.vases.index', compact('vases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.vases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $product = Product::create($request->only('price', 'cost'));
            if($request->hasFile('photo')){
                $this->handlePhotos($product, $request);
            }

            $gypsum_products = $product->gypsumProduct()->create(['type' => 'vase'])
                ->vase()
                ->create($request->only('name', 'color', 'gypsum_weight'));
        });

        return redirect()->route('products.vases.index')->with(['success'=>'Ваза успешно создана']);
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
        $vase = Vase::findorfail($id);

        return view('products.vases.edit', compact('vase'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vase = Vase::findOrFail($id);

        DB::transaction(function () use ($request, $vase) {
            if($request->hasFile('photo')){
                $this->updatePhoto($vase->gypsumProduct->product, $request->file('photo'));
            }
            $vase->gypsumProduct->product->update($request->only('price', 'cost'));
            $vase->update($request->only('name', 'color', 'gypsum_weight'));
        });
        return redirect()->route('products.vases.index')->with(['success'=>'Успешно изменено']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
