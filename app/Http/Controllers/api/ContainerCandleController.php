<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ContainerCandle;
use Illuminate\Http\Request;

class ContainerCandleController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');
        $container_color = $request->input('container_color');
        $fragrance = $request->input('fragrance');
        $volume = $request->input('volume');

        $query = ContainerCandle::with('candle.product.photos')
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
            ->when($container_color, function ($query) use ($container_color) {
                $query->where('container_color', $container_color);
            })
            ->when($fragrance, function ($query) use ($fragrance) {
                $query->where('fragrance', $fragrance);
            })
            ->when($volume, function ($query) use ($volume) {
                $query->where('volume', $volume);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $query->getCollection()->map(function ($containerCandle) {
            $product = $containerCandle->candle->product;

            return [
                'id_container' => $containerCandle->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);

        $availableContainer_Colors = ContainerCandle::distinct('container_color')->pluck('container_color');
        $availableFragrances = ContainerCandle::distinct('fragrance')->pluck('fragrance');
        $availableVolume = ContainerCandle::distinct('volume')->pluck('volume');

        return response()->json([
            'candles' => $query,
            'filters' => [
                'container_color' => $availableContainer_Colors,
                'fragrance' => $availableFragrances,
                'volume' => $availableVolume,
            ],
        ]);
    }
}
