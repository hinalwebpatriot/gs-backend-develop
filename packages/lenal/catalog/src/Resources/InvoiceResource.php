<?php

namespace lenal\catalog\Resources;

use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Services\PromoCodeService;
use Illuminate\Http\Resources\Json\JsonResource;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * @mixin \lenal\catalog\Models\Invoice
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sum = $this->convertedIncPrice();
        /** @var PromoCodeService $referralService */
        $referralService = app(PromoCodeService::class);
        $promoCode = $referralService->getCodeFromCookie();
        $referralDiscount = 0;
        $referral = null;
        if ($promoCode) {
            $referral = $referralService->getPromoCodeInterface($promoCode);
            if ($referral instanceof ReferralPromoCode) {
                $referralDiscount = $referralService->adaptiveDiscountToCurrency($sum);
            }
        }

        return [
            'id'                => $this->id,
            'alias'             => $this->alias,
            'email'             => $this->email,
            'raw_price'         => $this->convertedRawPrice(),
            'inc_price'         => $sum - $referralDiscount,
            'promo_code'        => $referral instanceof ReferralPromoCode ? $promoCode : null,
            'referral_discount' => $referralDiscount > 0 ? $referralDiscount : null,
            'status'            => $this->status,
            'services'          => InvoiceServiceResource::collection($this->services)
        ];
    }
}
