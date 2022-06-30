<?php


namespace GSD\Containers\Referral\Tasks;


use GSD\Containers\Referral\Data\Repositories\CustomerRepository;
use GSD\Containers\Referral\Data\Repositories\TowerCustomerRepository;
use GSD\Containers\Referral\DTO\CustomerDTO;
use GSD\Containers\Referral\DTO\RecipientDTO;
use GSD\Containers\Referral\Models\ReferralCustomer;
use GSD\Ship\Parents\Tasks\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class MakeCustomerTask
 * @package GSD\Containers\Referral\Tasks
 */
class MakeCustomerTask extends Task
{
    private TowerCustomerRepository $towerRepository;
    private CustomerRepository      $customerRepo;

    /**
     * CheckInTowerTask constructor.
     *
     * @param  TowerCustomerRepository  $towerRepository
     */
    public function __construct(TowerCustomerRepository $towerRepository, CustomerRepository $customerRepo)
    {
        $this->towerRepository = $towerRepository;
        $this->customerRepo = $customerRepo;
    }

    /**
     * Создает реферала в базе
     *
     * @param  string  $email
     *
     * @return ReferralCustomer|Builder
     * @throws \Exception
     */
    public function run(string $email, string $first_name, string $last_name): ReferralCustomer
    {
        return $this->customerRepo->create(new CustomerDTO([
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name
        ]));
    }
}