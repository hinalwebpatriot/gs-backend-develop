<?php


namespace GSD\Containers\Referral\Policies;


use App\User;
use GSD\Containers\Referral\Models\ReferralPayoutTransaction;

/**
 * Закрывает редактирование и создание выплат в ручном режиме
 *
 * Class ReferralPayoutTransactionPolicy
 * @package GSD\Containers\Referral\Policies
 */
class ReferralPayoutTransactionPolicy
{
    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, ReferralPayoutTransaction $transaction)
    {
        return false;
    }

    public function delete(User $user, ReferralPayoutTransaction $transaction)
    {
        return false;
    }

    public function view(User $user, ReferralPayoutTransaction $transaction)
    {
        return true;
    }
}