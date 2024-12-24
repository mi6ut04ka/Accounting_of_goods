<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Vase;
use Illuminate\Http\Request;

class VaseController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');
        $color = $request->input('color');


        $query = Vase::with('gypsumProduct.product.photos')
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
            ->when($color, function ($query) use ($color) {
                $query->where('color', $color);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $query->getCollection()->map(function ($vase) {
            $product = $vase->gypsumProduct->product;

            return [
                'id_vase' => $vase->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);

        $availableColors = Vase::distinct('color')->pluck('color');

        return response()->json([
            'gypsumProduct' => $query,
            'filters' => [
                'color' => $availableColors,
            ],
        ]);
    }
}
