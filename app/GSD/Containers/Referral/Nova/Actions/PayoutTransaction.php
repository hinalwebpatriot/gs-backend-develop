<?php

namespace GSD\Containers\Referral\Nova\Actions;

use DebugBar\DebugBar;
use GSD\Containers\Referral\Components\GiftPay\GiftPay;
use GSD\Containers\Referral\Models\ReferralCustomer;
use GSD\Containers\Referral\Services\PayoutService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;

class PayoutTransaction extends Action
{
    use InteractsWithQueue, Queueable;

    private PayoutService $payoutService;
    private GiftPay $giftPay;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
        $this->giftPay = new GiftPay(config('referral.main.giftCardSandbox'), config('referral.main.giftCardApiKey'));
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models->count() > 1) {
            return Action::danger('Please run this on only one user resource.');
        }
        /** @var ReferralCustomer $customer */
        $customer = $models->first();
        try {
            DB::beginTransaction();
            $amount = $fields->get('amount', 0);
            $transaction = $this->payoutService->payout($customer->id, $amount);
            $result = $this->giftPay->sendByEmail(
                $transaction->id,
                config('referral.main.giftCardEmailFrom'),
                $customer->email,
                $amount,
                config('referral.main.giftCardEmailText')
            );

            $this->markAsFinished($customer);
            DB::commit();
        } catch (\Throwable $e) {
            $this->markAsFailed($customer, $e);
            DB::rollBack();
            return Action::danger($e->getMessage());
        }
        return Action::message('Payout!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Number::make('Amount payout', 'amount')->required()
        ];
    }
}
