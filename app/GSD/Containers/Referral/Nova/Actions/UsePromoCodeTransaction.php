<?php

namespace GSD\Containers\Referral\Nova\Actions;

use DebugBar\DebugBar;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Services\PayoutService;
use GSD\Containers\Referral\Services\PromoCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;

class UsePromoCodeTransaction extends Action
{
    use InteractsWithQueue, Queueable;

    private PromoCodeService $promoCodeService;

    public function __construct(PromoCodeService $promoCodeService)
    {
        $this->promoCodeService = $promoCodeService;
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection     $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one promo code resource.');
        }
        /** @var ReferralPromoCode $promoCode */
        $promoCode = $models->first();
        DB::beginTransaction();
        try {
            if (!$promoCode->isActive()) {
                return Action::danger('Promo code was\'t apply because promo code used');
            }
            $amount = $fields->get('amount', 0);
            if ($amount < 1) {
                return Action::danger('Order total with GST so small');
            }
            $towerId = $fields->get('tower_id');
            if (!$towerId) {
                return Action::danger('Order total with GST so small');
            }
            $discount = $this->promoCodeService->calculateDiscount($fields->get('amount', 0));
            $this->promoCodeService
                ->makeTransaction($promoCode, null, $towerId, $amount, $discount);
            $this->promoCodeService->setUsed($promoCode);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Action::danger($e->getMessage());
        }
        DB::commit();
        return Action::message('Promo code was use');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Order total with GST', 'amount')->required(),
            Number::make('Tower Order ID', 'tower_id')->required()
        ];
    }
}
