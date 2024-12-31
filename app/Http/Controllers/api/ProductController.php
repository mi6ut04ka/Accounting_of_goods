<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function bestsellers()
    {
        $products = Product::withSum('sales', 'quantity')
            ->orderByDesc('sales_sum_quantity')
            ->take(4)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->photos->first()->url?? 0,
                    'in_stock' => $product->in_stock,
                ];
            });
        return response()->json($products);
    }

    public function new()
    {
        $products = Product::orderBy('created_at', 'desc')->take(4)->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'in_stock' => $product->in_stock,
                'image' => $product->photos->first()->url?? 0,
            ];
        });

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::find($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'in_stock' => $product->in_stock,
            'image' => $product->photos->first()->url?? 0,
            'description' => $product->description,
        ]);
    }

    public function search($name){
        $products = Product::all()->filter(function ($product) use ($name) {
            return str_contains(mb_strtolower($product->name), $name) == true;
        })->take(10)
            ->values()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                ];
            });

        return response()->json($products);
    }
}
