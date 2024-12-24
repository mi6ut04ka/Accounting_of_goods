<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): View
    {
        $orders = Order::when(request('status'), function ($query, $status) {
            $query->where('order_status', $status);
        })->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $products = Product::all();

        return view('orders.create', compact('products'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $this->orderService->createOrder($request->validated());

        return redirect()->route('orders.index', ['status' => 'pending'])->with('success', 'Заказ успешно создан.');
    }

    public function edit($id)
    {
        $order = Order::with('products')->findOrFail($id);
        $products = Product::all();

        return view('orders.edit', compact('order', 'products'));
    }

    public function updateStatus(Request $request, $order_id): RedirectResponse
    {
        $order = Order::findOrFail($order_id);

        try {
            $this->orderService->updateOrderStatus($order, $request->input('order_status'));
            return redirect()->back()->with('success', 'Статус заказа успешно обновлен!');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(StoreOrderRequest $request, $order_id): RedirectResponse
    {
        $order = Order::findOrFail($order_id);

        try {
            $this->orderService->updateOrder($order, $request->validated());
            return redirect()->route('orders.index', ['status' => $order->order_status])->with('success', 'Заказ успешно обновлен.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {
            $order->products()->detach();
            $order->delete();
        });

        return redirect()->route('orders.index', ['status' => $order->order_status])->with('success', 'Заказ успешно удалён.');
    }
}
