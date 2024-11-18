<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'customer_name' => $data['customer_name'],
                'deadline_date' => $data['deadline_date'],
                'order_date' => now(),
                'order_status' => 'pending',
                'note' => $data['note'] ?? null,
            ]);

            foreach ($data['products'] as $index => $productId) {
                $order->products()->attach($productId, [
                    'quantity' => $data['quantities'][$index],
                    'price' => Product::findOrFail($productId)->price,
                ]);
            }

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, string $newStatus): void
    {
        $oldStatus = $order->order_status;

        if ($oldStatus !== 'issued' && $newStatus === 'issued') {
            foreach ($order->products as $product) {
                if ($product->in_stock < $product->pivot->quantity) {
                    throw new \RuntimeException("Недостаточно товара '{$product->name}' на складе.");
                }
            }
        }

        DB::transaction(function () use ($order, $oldStatus, $newStatus) {
            $order->update(['order_status' => $newStatus]);

            foreach ($order->products as $product) {
                $quantity = $product->pivot->quantity;

                if ($oldStatus === 'issued' && $newStatus !== 'issued') {
                    Sale::where('order_id', $order->id)->where('product_id', $product->id)->delete();
                    $product->increment('in_stock', $quantity);
                } elseif ($oldStatus !== 'issued' && $newStatus === 'issued') {
                    Sale::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'time_of_sale' => now(),
                    ]);
                    $product->decrement('in_stock', $quantity);
                }
            }
        });
    }
}
