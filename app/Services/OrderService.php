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
    public function updateOrder(Order $order, array $data)
    {
        DB::transaction(function () use ($order, $data) {

            $order->update([
                'customer_name' => $data['customer_name'],
                'deadline_date' => $data['deadline_date'],
                'note' => $data['note'] ?? null,
            ]);
            $order->products()->detach();

            foreach ($data['products'] as $index => $productId) {
                $quantity = $data['quantities'][$index];
                $product = Product::findOrFail($productId);

                $order->products()->attach($productId, [
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }
        });
    }

    public function updateOrderStatus(Order $order, string $newStatus): void
    {
        $oldStatus = $order->order_status;

        if ($oldStatus !== 'issued' && $newStatus === 'issued') {
            foreach ($order->products as $product) {
                $orderQuantity = $product->pivot->quantity;

                if ($product->set) {
                    foreach ($product->set->items as $setItem) {
                        if ($setItem->product_id) {
                            $itemStock = $setItem->product->in_stock;
                            $requiredQuantity = $setItem->quantity * $orderQuantity;

                            if ($itemStock < $requiredQuantity) {
                                throw new \RuntimeException(
                                    "Недостаточно товара '{$setItem->product->name}' в наборе '{$product->name}'."
                                );
                            }
                        }
                    }
                }

                if (!$product->set && $product->in_stock < $orderQuantity) {
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

                    if ($product->set) {
                        foreach ($product->set->items as $setItem) {
                            if ($setItem->product_id) {
                                $setItem->product->incrementStock($setItem->quantity * $quantity);
                            }
                        }
                    }
                    $product->incrementStock($quantity);
                } elseif ($oldStatus !== 'issued' && $newStatus === 'issued') {
                    Sale::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'time_of_sale' => now(),
                    ]);
                    if ($product->set) {
                        foreach ($product->set->items as $setItem) {
                            if ($setItem->product_id) {
                                $setItem->product->decrementStock($setItem->quantity * $quantity);
                            }
                        }
                    }
                    $product->decrementStock($quantity);
                }
            }
        });
    }
}
