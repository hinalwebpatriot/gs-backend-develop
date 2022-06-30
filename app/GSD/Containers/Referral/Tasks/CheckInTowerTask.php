<?php


namespace GSD\Containers\Referral\Tasks;


use GSD\Containers\Referral\Data\Repositories\TowerCustomerRepository;
use GSD\Ship\Parents\Tasks\Task;

/**
 * Class CheckInTowerTask
 * @package GSD\Containers\Referral\Tasks
 */
class CheckInTowerTask extends Task
{
    private TowerCustomerRepository $repo;

    /**
     * CheckInTowerTask constructor.
     *
     * @param  TowerCustomerRepository  $repo
     */
    public function __construct(TowerCustomerRepository $repo)
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