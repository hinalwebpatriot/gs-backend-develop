<?php

namespace GSD\Containers\Referral\Observers;


use Exception;
use GSD\Containers\Referral\Data\Repositories\PromoCodeRepository;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Values\GenerateReferralPromoCodeValue;
use GSD\Ship\Exceptions\BadRequestHttpException;

/**
 * Class PromoCodeObserver
 * @package GSD\Containers\Referral\Observers
 */
class PromoCodeObserver
{
    /**
     * @param  ReferralPromoCode  $promoCode
     *
     * @throws Exception
     */
    public function creating(ReferralPromoCode $promoCode)
    {
        if (!$promoCode->code) {
            $owner = $promoCode->owner;
            /** @var PromoCodeRepository $repo */
            $repo = app(PromoCodeRepository::class);
            $code = new GenerateReferralPromoCodeValue($owner->first_name, $owner->last_name);
            do {
                $promoCode->code = $code->getValue();
            } while ($repo->isExist($promoCode->code));
        }
    }

    public function updated(ReferralPromoCode $promoCode)
    {
        if ($promoCode->isDirty('is_used') && $promoCode->is_used) {
            $promoCode->sendNotifyUsed();
        }
    }
}
