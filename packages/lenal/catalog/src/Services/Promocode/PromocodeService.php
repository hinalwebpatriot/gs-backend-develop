<?php

namespace lenal\catalog\Services\Promocode;


use GSD\Containers\Referral\Interfaces\PromoCodeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use lenal\catalog\Mail\PromoCodeConfirmation;
use lenal\catalog\Models\Promocode;

class PromocodeService
{
    /**
     * @return PromoCodeInterface
     */
    public function restorePromocode()
    {
        $code = request()->cookie('promocode');

        if (!$code) {
            return null;
        }

        /**
         * TODO Страшный говнокод из-за писанины пейко, нужно будет переписать
         * @var \GSD\Containers\Referral\Services\PromoCodeService $promoService
         */
        $promoService = app(\GSD\Containers\Referral\Services\PromoCodeService::class);
        $codeObj = $promoService->getPromoCodeInterface($code);
        if (!$codeObj || !$codeObj->isActive()) {
            return null;
        }

        return $codeObj;
    }

    public function create(PromoCodeInterface $promocode, $confirmCode = null)
    {
        $data = [
            'with_confirmation' => 0,
            'message' => trans('api.promocode-applied'),
        ];

        if ($promocode instanceof Promocode) {
            if ($promocode->isPersonal()) {
                if (!$confirmCode) {
                    $promocode->generateConfirmCode();
                    $promocode->save();
                    $data['with_confirmation'] = 1;
                    $data['message'] = trans('api.confirm-promocode');

                    Mail::to($promocode->personal_email)->send(new PromoCodeConfirmation($promocode->confirm_code));
                } elseif ($promocode->confirmation($confirmCode)) {
                    $promocode->incrementTimes();
                    $promocode->save();
                }
            } elseif ($promocode->isGroup()) {
                $promocode->incrementTimes();
                $promocode->save();
            }
        }
        return $data;
    }

    public function apply(Collection $cartItems)
    {
        $promocode = $this->restorePromocode();
        if ($promocode && $promocode instanceof Promocode && $promocode->isActive()) {
            PromocodeApplying::instance($promocode->kind)->apply($cartItems, $promocode);
        }

        return $cartItems;
    }

    public function clearCookie()
    {
        return Cookie::forget('promocode');
    }
}