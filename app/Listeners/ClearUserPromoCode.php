<?php

namespace App\Listeners;

use App\Events\PromoCodeUpdated;
use Illuminate\Auth\Events\Logout;

class ClearUserPromoCode
{
    public function handle(Logout $event)
    {
        $user = $event->user;
        if ($user && $user->applied_promo_code_id) {
            $user->update(['applied_promo_code_id' => null]);

            event(new PromoCodeUpdated($user));
        }
    }
}
