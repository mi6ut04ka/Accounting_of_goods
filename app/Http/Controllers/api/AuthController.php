<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use http\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function index (Request $request)
    {
        $user = \Auth::user();
        return response()->json($user);
    }

    public function getOrders()
    {
        $user = \Auth::user();

        $orders = Order::with('products')
        ->where('user_id', $user->id)
            ->get()
            ->map(function ($order) {
                $totalAmount = $order->products->reduce(function ($carry, $product) {
                    return $carry + ($product->pivot->quantity * $product->pivot->price);
                }, 0);

                return [
                    'id' => $order->id,
                    'status' => $order->order_status,
                    'date' => $order->order_date,
                    'totalAmount' => $totalAmount,
                ];
            });

        return response()->json($orders);
    }

}
