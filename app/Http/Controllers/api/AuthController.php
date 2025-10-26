<?php

namespace App\Http\Controllers\api;

use App\Events\PromoCodeUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        event(new Registered($user));

        Auth::login($user);

        session()->regenerate();

        return response()->json(['user'=>$user, 'message' => 'Пользователь зарегистрирован. Подтвердите email.'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Недействительные учетные данные'], 401);
        }

        $request->session()->regenerate();

        event(new PromoCodeUpdated(Auth::user()));

        Auth::user()->notifications()->create([
            'title' => 'Успешный вход',
            'body' => 'Вы успешно вошли в систему ' . now() . ' с IP: ' . $request->ip(),
        ]);

        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->regenerate();

        return response()->json(['message' => 'Вы вышли из системы']);
    }

    public function index(Request $request)
    {
        $userArray = $request->user()->toArray();
        $userWithPromo = $request->user()->load('appliedPromoCode');
        $userArray['promoCode'] = $userWithPromo->appliedPromoCode ? $userWithPromo->appliedPromoCode->code : null;



        return response()->json($userArray);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
        ]);

        $user = Auth::user();

        $user->update($validated);

        return response()->json(['success' => "Данные успешно обновлены"]);
    }

}
