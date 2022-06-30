<?php

namespace lenal\catalog\Services\Promocode;


use Illuminate\Support\Collection;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\Promocode;

class PersonalPromocode extends PromocodeApplying
{
    public function apply(Collection $cartItems, Promocode $promocode)
    {
        $availableItemsForDiscount = $this->countPromocodeItems($cartItems);

        foreach ($cartItems->getIterator() as $cartItem) {
            if (!is_a($cartItem, IPromocode::class)) {
                continue;
            }

            if ($promocode->hasDiscount($cartItem)) {
                $promocode->fillDiscount($cartItem, $availableItemsForDiscount);
            }
        }
    }
}