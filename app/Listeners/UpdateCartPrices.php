<?php

namespace App\Listeners;

use App\Events\PromoCodeUpdated;
use App\Models\CartItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateCartPrices
{
    /**
     * Create the event listener.
     */
    public function __construct(PromoCodeUpdated $event)
    {


    }

    /**
     * Handle the event.
     */
    public function handle(PromoCodeUpdated $event): bool
    {
        $user = $event->user;

        $promoCode = $user->appliedPromoCode;

        $cartItems = CartItem::where('user_id', $user->id)->get();

        $hasEligibleItems = false;

        foreach ($cartItems as $item) {
            $originalPrice = $item->product->price;
            $discountedPrice = $originalPrice;

            if ($promoCode) {
                $applicableCategories = json_decode($promoCode->applicable_categories, true);

                if (is_array($applicableCategories) && (in_array('all', $applicableCategories) || in_array($item->product->category->id, $applicableCategories))) {
                    $discountedPrice = $originalPrice - ($originalPrice * ($promoCode->discount / 100));
                    $hasEligibleItems = true;
                }
            }


            $item->update([
                'discount_price' => $discountedPrice,
            ]);
        }

        if (!$hasEligibleItems) {
            $user->update(['applied_promo_code_id' => null]);
        }

        return $hasEligibleItems;
    }
}
