<?php

namespace lenal\promo_registration\Repositories;


use lenal\promo_registration\Models\PromoRegistrationText;

class PromoRegistrationRepository
{
    public function firstActive()
    {
        /** @var PromoRegistrationText $text */
        $text = PromoRegistrationText::query()
            ->where('is_active', 1)
            ->first();

        if (!$text) {
            $text = new PromoRegistrationText();
        }

        return $text;
    }
}