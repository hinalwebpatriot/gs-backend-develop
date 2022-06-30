<?php

namespace lenal\catalog\Services\Promocode;


use Illuminate\Support\Collection;
use lenal\catalog\Models\IPromocode;
use lenal\catalog\Models\Promocode;

abstract class PromocodeApplying
{
    abstract public function apply(Collection $cartProductCollection, Promocode $promocode);

    public static function instance($kind)
    {
        if ($kind == Promocode::KIND_PERSONAL) {
            return new PersonalPromocode();
        } elseif ($kind == Promocode::KIND_GROUP) {
            return new GroupPromocode();
        }

        return null;
    }

    public function countPromocodeItems(Collection $cartItems)
    {
        return count(array_filter($cartItems->all(), function($cartItem) {
            return is_a($cartItem, IPromocode::class);
        }));
    }
}