<?php

namespace App\Http\Controllers\api;

use App\Events\PromoCodeUpdated;
use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoCodeController extends Controller
{
    public function applyPromoCode(Request $request)
    {
        $user = $request->user();
        $promoCodeValue = $request->input('promoCode');
        if (empty($promoCodeValue)) {
            return response()->json(['error' => 'Введите промокод'], 400);
        }

        $promoCode = PromoCode::where('code', $promoCodeValue)->first();

        if (!$promoCode) {
            return response()->json(['error' => 'Промокод не найден'], 404);
        }
        $cartItems = $user->cartItems;

        try {
            DB::beginTransaction();

            if (!$promoCode->is_active || now()->lt($promoCode->start_date) || now()->gt($promoCode->end_date)) {
                return response()->json(['error' => 'Промокод недействителен'], 400);
            }

            $cartTotal = $cartItems->sum(fn($item) => $item->quantity * $item->price);
            if ($promoCode->min_order_amount && $cartTotal < $promoCode->min_order_amount) {
                return response()->json(['error' => 'Минимальная сумма заказа для применения промокода: ' . $promoCode->min_order_amount . ' руб.'], 400);
            }

            $user->update(['applied_promo_code_id' => $promoCode->id]);

            $discountApplied = event(new PromoCodeUpdated($user));

            if (!$discountApplied) {
                \DB::rollBack();
                return response()->json(['error' => 'Ни один товар в корзине не подходит под условия промокода'], 400);
            }

            DB::commit();

            return response()->json(['success' => 'Промокод успешно применен!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при применении промокода: ' . $e->getMessage()], 500);
        }
    }

    public function removePromoCode(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Требуется авторизация'], 401);
        }

        if (!$user->applied_promo_code_id) {
            return response()->json(['error' => 'Нет применённого промокода'], 400);
        }

        try {
            DB::beginTransaction();

            $user->update(['applied_promo_code_id' => null]);

            event(new PromoCodeUpdated($user));

            DB::commit();

            return response()->json(['success' => 'Промокод успешно удалён']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении промокода: ' . $e->getMessage()], 500);
        }
    }
}
