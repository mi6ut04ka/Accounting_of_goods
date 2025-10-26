<?php

namespace App\Http\Controllers\api;

use App\Events\PromoCodeUpdated;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Получить список товаров в корзине.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.photos', 'product.category'])
            ->get();

        $modifiedData = $cartItems->map(function ($cartItem) use ($user) {
            $product = $cartItem->product;

            return [
                'id' => $product->id,
                'quantity' => $cartItem->quantity,
                'inStock' => $product->in_stock,
                'name' => $cartItem->product_name,
                'isFavorite' => $user->favorites()->where('product_id', $product->id)->exists(),
                'price' => $cartItem->price,
                'photos' => $product->photos,
                'categorySlug' => $product->category?->slug,
                'discount_price' => $cartItem->discount_price,
            ];
        });

        return response()->json($modifiedData);
    }

    /**
     * Добавить товар в корзину.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'discount_price' => $product->price,
                'quantity' => 1,
            ]);
        }


        event(new PromoCodeUpdated($user));

        return response()->json([
            'message' => 'Товар добавлен в корзину',
            'cartItem' => $cartItem,
        ]);
    }

    /**
     * Удалить товар из корзины.
     */
    public function destroy($id)
    {
        $user = \Auth::user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $id)
            ->firstOrFail();

        $cartItem->delete();

        event(new PromoCodeUpdated($user));

        return response()->json(['message' => 'Товар удален из корзины']);
    }

    /**
     * Уменьшить количество товара в корзине (или удалить).
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = $request->user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Товар в корзине не найден'], 404);
        }
        if ($cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $cartItem->refresh();
        } else {
            $cartItem->delete();
            return response()->json(['message' => 'Товар удален из корзины']);
        }

        return response()->json([
            'message' => 'Количество товара обновлено',
            'cartItem' => $cartItem,
        ]);
    }
}
