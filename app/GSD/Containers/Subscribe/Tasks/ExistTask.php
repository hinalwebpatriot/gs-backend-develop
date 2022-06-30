<?php


namespace GSD\Containers\Subscribe\Tasks;


use GSD\Containers\Subscribe\Data\Repositories\SubscribeRepository;
use GSD\Ship\Parents\Tasks\Task;

/**
 * Проверяет подписал или нет емайл
 *
 * Class ExistTask
 * @package GSD\Containers\Subscribe\Tasks
 */
class ExistTask extends Task
{
    private SubscribeRepository $repo;

    public function __construct(SubscribeRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param  string  $email
     *
     * @return bool
     */
    public function run(string $email): bool
    {
        return $this->repo->isExist($email);
    }
}