<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\FavoritesItem;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoritesItemController extends Controller
{
    public function index()
    {
        $user = \Auth::user();

        $favoritesItems = FavoritesItem::where('user_id', $user->id)
            ->with(['product.photos'])
            ->get();

        $modifiedData = $favoritesItems->map(function ($favoritesItem) {
            $product = $favoritesItem->product;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'categorySlug' => $product->category->slug,
                'favorite' => true,
                'price' => $product->price,
                'photos' => $product->photos,
                'in_stock' => $product->in_stock,
            ];
        });

        return response()->json($modifiedData);

    }

    public function show(Request $request, Product $product)
    {
       if($request->user()){
           return response()->json([
               'favorite' => $request->user()->favorites()->where('product_id', $product->id)->exists(),
           ]);
       }
        return response()->json();
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = \Auth::user();

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        $favoriteItem = FavoritesItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($favoriteItem) {
            return response()->json(['error'=>'Товар уже у избранном']);
        } else {
            $favoriteItem = FavoritesItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'product_name' => $product->name,
            ]);
        }

        return response()->json(['message' => 'Товар добавлен в избранное', 'favoriteItem' => $favoriteItem]);
    }
    public function destroy($id)
    {

        $user = \Auth::user();

        $favoritesItem = FavoritesItem::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        if (!$favoritesItem) {
            return response()->json(['error' => 'Товар в избранном не найден'], 404);
        }

        $favoritesItem->delete();

        return response()->json(['message' => 'Товар удален из избранного']);
    }
}
