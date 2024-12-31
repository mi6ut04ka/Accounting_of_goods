<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use Illuminate\Http\Request;

class StandController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');
        $stand_type = $request->input('stand_type');
        $color = $request->input('color');


        $query = Stand::with('gypsumProduct.product.photos')
            ->when($priceFrom, function ($query) use ($priceFrom) {
                $query->whereHas('gypsumProduct.product', function ($query) use ($priceFrom) {
                    $query->where('price', '>=', $priceFrom);
                });
            })
            ->when($priceTo, function ($query) use ($priceTo) {
                $query->whereHas('gypsumProduct.product', function ($query) use ($priceTo) {
                    $query->where('price', '<=', $priceTo);
                });
            })
            ->when($inStock, function ($query) {
                $query->whereHas('gypsumProduct.product', function ($query) {
                    $query->where('in_stock', true);
                });
            })
            ->when($stand_type, function ($query) use ($stand_type) {
                    $query->where('stand_type', $stand_type);
            })
            ->when($color, function ($query) use ($color) {
                $query->where('color', $color);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $query->getCollection()->map(function ($stand) {
            $product = $stand->gypsumProduct->product;

            return [
                'id_stand' => $stand->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);

        $availableColors = Stand::distinct('color')->pluck('color');
        $availableStandTypes = Stand::distinct('stand_type')->pluck('stand_type');


        return response()->json([
            'gypsumProduct' => $query,
            'filters' => [
                'color' => $availableColors,
                'stand_type' => $availableStandTypes,
            ],
        ]);
    }
}
