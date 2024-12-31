<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\MoldedCandle;
use Illuminate\Http\Request;

class MoldedCandleController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');
        $fragrance = $request->input('fragrance');

        $query = MoldedCandle::with('candle.product.photos')
            ->when($priceFrom, function ($query) use ($priceFrom) {
                $query->whereHas('candle.product', function ($query) use ($priceFrom) {
                    $query->where('price', '>=', $priceFrom);
                });
            })
            ->when($priceTo, function ($query) use ($priceTo) {
                $query->whereHas('candle.product', function ($query) use ($priceTo) {
                    $query->where('price', '<=', $priceTo);
                });
            })
            ->when($inStock, function ($query) {
                $query->whereHas('candle.product', function ($query) {
                    $query->where('in_stock', true);
                });
            })
            ->when($fragrance, function ($query) use ($fragrance) {
                if($fragrance == 'Без аромата'){
                    $query->where('fragrance', null);
                }
                else{
                    $query->where('fragrance', $fragrance);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

            $modifiedData = $query->getCollection()->map(function ($moldedCandle) {
            $product = $moldedCandle->candle->product;

            return [
                'id_molded' => $moldedCandle->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);

        $availableFragrances = MoldedCandle::distinct('fragrance')->pluck('fragrance');

        $availableFragrances = $availableFragrances->map(function ($fragrance) {
            return $fragrance === null ? 'Без аромата' : $fragrance;
        });

        return response()->json([
            'candles' => $query,
            'filters' => [
                'fragrance' => $availableFragrances,
            ],
        ]);
    }
}
