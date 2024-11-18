<?php

namespace App\Http\Controllers;


use App\Models\MoldedCandle;
use App\Models\Product;
use App\Traits\HandlesProductPhotos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MoldedCandlesController extends Controller
{
    use HandlesProductPhotos;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $currentPage = request()->get('page');

        $perPage = ($currentPage == 1 || $currentPage === null) ? 11 : 12;

        $molded_candles = MoldedCandle::with('candle.product')->paginate($perPage);

        return view('products.molded_candles.index', compact('molded_candles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.molded_candles.create');
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

            $moldedCandle = $product->candle()->create(['type' => 'molded'])
                ->moldedCandle()
                ->create($request->only(['name', 'wax_weight', 'fragrance', 'fragrance_percentage']));
        });

        return redirect()->route('products.molded_candles.index')->with(['success'=>'Формовая свеча успешно создана']);
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
        $molded = MoldedCandle::findorfail($id);

        return view('products.molded_candles.edit', compact('molded'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $molded = MoldedCandle::findOrFail($id);

        DB::transaction(function () use ($molded, $request) {

            if($request->hasFile('photo')){
                $this->updatePhoto($molded->candle->product, $request->file('photo'));
            }
            $molded->candle->product->update($request->only(['price', 'cost']));
            $molded->update($request->only([
                'name',
                'wax_weight',
                'fragrance',
                'fragrance_percentage'
            ]));
        });


        return redirect()->route('products.molded_candles.index')->with(['success', 'Успешно изменено']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
