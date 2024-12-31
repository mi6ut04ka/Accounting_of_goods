<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stand;
use App\Models\Statue;
use Illuminate\Http\Request;

class StatuetteController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');
        $statue_type = $request->input('statue_type');
        $color = $request->input('color');


        $query = Statue::with('gypsumProduct.product.photos')
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
            ->when($statue_type, function ($query) use ($statue_type) {
                $query->where('statue_type', $statue_type);
            })
            ->when($color, function ($query) use ($color) {
                $query->where('color', $color);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $query->getCollection()->map(function ($statuette) {
            $product = $statuette->gypsumProduct->product;

            return [
                'id_statuette' => $statuette->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);

        $availableColors = Statue::distinct('color')->pluck('color');
        $availableStatueTypes = Statue::distinct('statue_type')->pluck('statue_type');


        return response()->json([
            'gypsumProduct' => $query,
            'filters' => [
                'color' => $availableColors,
                'statue_type' => $availableStatueTypes,
            ],
        ]);
    }
}
