<?php


namespace GSD\Containers\Subscribe\Tasks;


use GSD\Containers\Subscribe\Data\Repositories\SubscribeRepository;
use GSD\Containers\Subscribe\DTO\SubscriberDTO;
use GSD\Containers\Subscribe\Exceptions\CreateException;
use GSD\Containers\Subscribe\Models\Subscribe;
use GSD\Ship\Parents\Tasks\Task;

/**
 * Подписывает емаил на рассылку
 *
 * Class SubscribeTask
 * @package GSD\Containers\Subscribe\Tasks
 */
class SubscribeTask extends Task
{
    private SubscribeRepository $repo;

    public function __construct(SubscribeRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param  SubscriberDTO  $dto
     *
     * @return Subscribe
     * @throws CreateException
     */
    public function run(SubscriberDTO $dto): Subscribe
    {
        return $this->repo->create($dto);
    }
}