<?php


namespace GSD\Containers\Referral\Services;

use GSD\Containers\Referral\Data\Repositories\CustomerRepository;
use GSD\Containers\Referral\Exceptions\PayoutException;
use GSD\Containers\Referral\Models\ReferralPayoutTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class PayoutService
 * @package GSD\Containers\Referral\Services
 */
class PayoutService
{
    const MIN_PAYOUT = 1;

    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Выводит деньги со счета и создает транзакцию вывода
     *
     * @param  int    $customer_id
     * @param  float  $amount
     *
     * @throws PayoutException
     */
    public function payout(int $customer_id, float $amount): ReferralPayoutTransaction
    {
        if ($amount < self::MIN_PAYOUT) {
            throw new PayoutException('Amount invalid');
        }

        $customer = $this->customerRepository->getById($customer_id);
        if (!$customer) {
            throw new PayoutException('Customer not found');
        }

        if ($customer->balance < $amount) {
            throw new PayoutException('Amount more then customer balance');
        }

        DB::beginTransaction();
        try {
            $payout = $customer->payouts()->create([
                'payout' => $amount
            ]);
            $customer->balance -= $amount;
            $customer->save();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            throw new PayoutException($e->getMessage());
        }
        DB::commit();
        return $payout;
    }
}