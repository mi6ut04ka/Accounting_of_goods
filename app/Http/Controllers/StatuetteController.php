<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Statue;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatuetteController extends Controller
{
    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $currentPage = request()->get('page');

        $perPage = ($currentPage == 1 || $currentPage === null) ? 11 : 12;

        $statuettes = Statue::with('gypsumProduct.product')->paginate($perPage);
        return view('products.statuettes.index', compact('statuettes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.statuettes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $product = Product::create($request->only('price', 'cost'));
            if($request->hasFile('photo')){
                $this->handlePhotos($product, $request);
            }

            $gypsum_products = $product->gypsumProduct()->create(['type' => 'statue'])
                ->statue()
                ->create($request->only('statue_type', 'color', 'gypsum_weight'));
        });

        return redirect()->route('products.statuettes.index')->with('success', 'Статуэтка успешно создана');
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
    public function edit(string $id): View
    {
        $statuette = Statue::findorfail($id);
        return view('products.statuettes.edit', compact('statuette'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $statuette = Statue::findOrFail($id);

        DB::transaction(function () use ($request, $statuette) {
            if($request->hasFile('photo')){
                $this->updatePhoto($statuette->gypsumProduct->product, $request->file('photo'));
            }
            $statuette->gypsumProduct->product->update($request->only('price', 'cost'));
            $statuette->update($request->only('statue_type', 'color', 'gypsum_weight'));
        });
        return redirect()->route('products.statuettes.index')->with(['success'=>'Успешно изменено']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
