<?php

namespace GSD\Containers\Referral\Observers;


use Exception;
use GSD\Containers\Referral\Data\Repositories\PromoCodeRepository;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Models\ReferralTransaction;
use GSD\Containers\Referral\Services\CustomerService;
use GSD\Containers\Referral\Values\GenerateReferralPromoCodeValue;
use GSD\Ship\Exceptions\BadRequestHttpException;

/**
 * Class TransactionObserver
 * @package GSD\Containers\Referral\Observers
 */
class TransactionObserver
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * @param  ReferralTransaction $transaction
     *
     * @throws Exception
     */
    public function saved(ReferralTransaction $transaction)
    {
        if (
            $transaction->isDirty('approved_at') &&
            $transaction->isApproved &&
            !$transaction->getOriginal('approved_at')
        ) {
            $this->customerService->updateBalance($transaction->owner_id, $transaction->payment);
        }
    }
}
