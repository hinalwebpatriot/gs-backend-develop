<?php


namespace GSD\Containers\Referral\Services;

use GSD\Containers\Referral\Data\Repositories\CustomerRepository;
use GSD\Containers\Referral\Exceptions\CustomerException;
use GSD\Containers\Referral\Exceptions\PayoutException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerService
 * @package GSD\Containers\Referral\Services
 */
class CustomerService
{
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->repository = $customerRepository;
    }

    /**
     * Обновляет баланс реферала
     * @param  int    $customer_id
     * @param  float  $amount
     *
     * @throws CustomerException
     */
    public function updateBalance(int $customer_id, float $amount)
    {
        $customer = $this->repository->getById($customer_id);
        if (!$customer) {
            throw new CustomerException('Customer not found');
        }

        $customer->balance += $amount;
        if (!$customer->save()) {
            throw new CustomerException('Failed to update customer balance');
        }
    }
}