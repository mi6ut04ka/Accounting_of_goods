<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class CartItemController extends Controller
{
    /**
     * Получить список товаров в корзине.
     */
    public function index(Request $request)
    {
        $user = \Auth::user();

        $cartItems = CartItem::where('user_id', $user->id)
            ->with(['product.photos'])
            ->get();

        $modifiedData = $cartItems->map(function ($cartItem) {
            $product = $cartItem->product;

            return [
                'id' => $product->id,
                'quantity' => $cartItem->quantity,
                'name' => $cartItem->product_name,
                'price' => $cartItem->price,
                'image' => $product->photos->first()->url ?? null,
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

        $user = \Auth::user();

        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Товар добавлен в корзину', 'cartItem' => $cartItem]);
    }

    /**
     * Удалить товар из корзины.
     */
    public function destroy($id)
    {
        $user = \Auth::user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Товар в корзине не найден'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Товар удален из корзины']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = \Auth::user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Товар в корзине не найден'], 404);
        }

        $cartItem->quantity -= 1;

        if ($cartItem->quantity <= 0) {
            $cartItem->delete();
            return response()->json(['message' => 'Товар удален из корзины']);
        }

        $cartItem->save();

        return response()->json([
            'message' => 'Количество товара обновлено',
            'cartItem' => $cartItem,
        ]);
    }
}
