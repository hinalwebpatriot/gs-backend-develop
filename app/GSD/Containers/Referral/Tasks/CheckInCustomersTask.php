<?php


namespace GSD\Containers\Referral\Tasks;


use GSD\Containers\Referral\Data\Repositories\CustomerRepository;
use GSD\Containers\Referral\Data\Repositories\TowerCustomerRepository;
use GSD\Ship\Parents\Tasks\Task;

/**
 * Class CheckInCustomersTask
 * @package GSD\Containers\Referral\Tasks
 */
class CheckInCustomersTask extends Task
{
    private CustomerRepository $repo;

    /**
     * CheckInTowerTask constructor.
     *
     * @param  CustomerRepository  $repo
     */
    public function __construct(CustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Проверяет существует ли клиент с таким емейлом в данных с базы Tower
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function run(string $email): bool
    {
        return $this->repo->existByEmail($email);
    }
}