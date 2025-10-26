<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = request()->user();

        $orders = Order::with('products')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deliveryMethod' => 'required|string',
            'note' => 'string|nullable',
            'city' => 'string|nullable',
            'street' => 'string|nullable',
            'house_number' => 'string|nullable',
            'postal_code' => 'string|nullable',
            'name' => 'string|required',
        ]);

        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)->get();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_name' => $validated['name'],
                'user_id' => $user->id,
                'order_date' => now(),
                'order_status' => 'pending',
                'order_number' => $this->generateUniqueOrderNumber(),
                'pay_status' => 'unpaid',
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($cartItems as $cartItem) {
                $productPrice = $cartItem->price == $cartItem->discount_price ? $cartItem->price : $cartItem->discount_price;
                $order->products()->attach($cartItem->product_id, [
                    'quantity' => $cartItem->quantity,
                    'price' => $productPrice,
                ]);
                $cartItem->delete();
            }

            if ($validated['deliveryMethod'] === 'delivery') {
                $user->update([
                    'city' => $validated['city'],
                    'street' => $validated['street'],
                    'house_number' => $validated['house_number'],
                    'postal_code' => $validated['postal_code'],
                ]);
            }

            $user->notifications()->create([
                'title' => 'Заказ оформлен',
                'body' => 'Ваш заказ #' . $order->order_number . ' был успешно создан.',
            ]);

            DB::commit();
            return response()->json(['success' => 'Заказ успешно оформлен']);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error("Ошибка при создании заказа: " . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    private function generateUniqueOrderNumber()
    {
        do {
            $number = rand(100000, 999999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
