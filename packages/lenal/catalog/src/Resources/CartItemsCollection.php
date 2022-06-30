<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 2/7/19
 * Time: 2:42 PM
 */

namespace lenal\catalog\Resources;

use Carbon\Carbon;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Services\PromoCodeService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use lenal\catalog\Services\DeliveryTimeService;
use lenal\PriceCalculate\Facades\CurrencyRate;

class CartItemsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $deliveryPeriod = app(DeliveryTimeService::class)->maxForProductCollection($this->collection);

        /** @var PromoCodeService $referralService */
        $referralService = app(PromoCodeService::class);

        $collection = ProductResource::collection($this->collection);
        $order_sum = $collection->collection
            ->map(function ($item) use ($request) {
                $resource = $item->toArray($request);
                return $resource['price']['count'];
            })
            ->sum();
        $cartDiscount = $collection->collection
            ->map(function ($item) use ($request) {
                $resource = $item->toArray($request);
                return $resource['price']['old_count'] ?: $resource['price']['count'];
            })
            ->sum() - $order_sum;

        $promoCode = $referralService->getCodeFromCookie();
        $referralDiscount = null;
        if ($promoCode) {
            $referral = $referralService->getPromoCodeInterface($promoCode);
            if ($referral instanceof ReferralPromoCode) {
                $referralDiscount = $referralService->adaptiveDiscountToCurrency($order_sum);
            }
        }
        return [
            'products_list'  => $collection,
            'products_count' => $this->collection->count(),
            'products_total' => [
                'count'             => $referralService->applyReferralDiscount($order_sum),
                'referral_discount' => $referralDiscount,
                'cart_discount'     => $cartDiscount > 0 ? $cartDiscount : null,
                'promo_code'        => $promoCode,
                'currency'          => CurrencyRate::getUserCurrency()
            ],
            'delivery'       => [
                'info' => trans('api.cart-delivery-period', $deliveryPeriod->deliveryPeriodParams()),
                'date' => Carbon::now()->addDays($deliveryPeriod->maxDays())->format('M d, Y'),
            ],
        ];
    }
}
