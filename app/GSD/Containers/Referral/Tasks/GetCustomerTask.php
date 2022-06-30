<?php


namespace GSD\Containers\Referral\Tasks;


use GSD\Containers\Referral\Data\Repositories\CustomerRepository;
use GSD\Containers\Referral\Models\ReferralCustomer;
use GSD\Ship\Parents\Tasks\Task;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class GetCustomerTask
 * @package GSD\Containers\Referral\Tasks
 */
class GetCustomerTask extends Task
{
    private CustomerRepository $repo;

    /**
     * GetCustomerTask constructor.
     *
     * @param  CustomerRepository  $repo
     */
    public function __construct(CustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Возвращает реверала по емейлу
     *
     * @param  string  $email
     *
     * @return ReferralCustomer|Builder|array
     */
    public function run(string $email): ?ReferralCustomer
    {
        return $this->repo->getByEmail($email);
    }
}