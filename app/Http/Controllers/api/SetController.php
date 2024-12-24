<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\MoldedCandle;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index(Request $request)
    {
        $priceFrom = $request->input('priceFrom');
        $priceTo = $request->input('priceTo');
        $inStock = $request->input('inStock');

        $query = Set::with('product.photos')
            ->when($priceFrom, function ($query) use ($priceFrom) {
                $query->whereHas('product', function ($query) use ($priceFrom) {
                    $query->where('price', '>=', $priceFrom);
                });
            })
            ->when($priceTo, function ($query) use ($priceTo) {
                $query->whereHas('product', function ($query) use ($priceTo) {
                    $query->where('price', '<=', $priceTo);
                });
            })
            ->when($inStock, function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('in_stock', true);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $modifiedData = $query->getCollection()->map(function ($set) {
            $product = $set->product;

            return [
                'id_set' => $set->id,
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->photos->first()->url ?? 0,
                'in_stock' => $product->in_stock,
            ];
        });

        $query->setCollection($modifiedData);


        return response()->json([
            'sets' => $query
        ]);
    }
}