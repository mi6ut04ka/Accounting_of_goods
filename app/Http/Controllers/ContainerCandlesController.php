<?php

namespace App\Http\Controllers;

use App\Models\ContainerCandle;
use App\Models\Product;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainerCandlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HandlesProductPhotos;

    public function index()
    {
        $currentPage = request()->get('page');

        $perPage = ($currentPage == 1 || $currentPage === null) ? 11 : 12;

        $container_candles = ContainerCandle::with('candle.product')->paginate($perPage);

        return view('products.container_candles.index', compact('container_candles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.container_candles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        DB::transaction(function () use ($request) {
            $product = Product::create($request->only(['price', 'cost']));

            if($request->hasFile('photo')){
                $this->handlePhotos($product, $request);
            }

            $candle = $product->candle()->create(['type' => 'container']);

            $candle->containerCandle()->create($request->only([
                'volume',
                'fragrance',
                'fragrance_percentage',
                'container_color',
                'box_size',
                'decor_description',
                'type_of_wax',
            ]));
        });

        return redirect()->route('products.container_candles.index')->with('success', 'Контейнерная свеча успешно создана');
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
        $containerCandle = ContainerCandle::find($id);

        return view('products.container_candles.edit', compact('containerCandle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $containerCandle = ContainerCandle::findOrFail($id);

        DB::transaction(function () use ($containerCandle, $request) {

            if ($request->hasFile('photo')) {
                $this->updatePhoto($containerCandle->candle->product, $request->file('photo'));
            }
            $containerCandle->candle->product->update($request->only(['price', 'cost']));
            $containerCandle->update($request->only([
                'volume',
                'fragrance',
                'fragrance_percentage',
                'container_color',
                'box_size',
                'decor_description',
                'type_of_wax',
            ]));
        });

        return redirect()
            ->route('products.container_candles.index')
            ->with('success', 'Успешно изменено');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
