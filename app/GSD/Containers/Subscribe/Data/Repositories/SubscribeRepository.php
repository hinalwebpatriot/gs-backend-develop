<?php


namespace GSD\Containers\Subscribe\Data\Repositories;


use GSD\Containers\Subscribe\DTO\SubscriberDTO;
use GSD\Containers\Subscribe\Exceptions\CreateException;
use GSD\Containers\Subscribe\Models\Subscribe as Model;
use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SubscribeRepository
 * @package GSD\Containers\Subscribe\Data\Repositories
 */
class SubscribeRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * Создает подписчика
     *
     * @param  SubscriberDTO  $dto
     *
     * @return Model|Builder
     * @throws CreateException
     */
    public function create(SubscriberDTO $dto): Model
    {
        $model = $this->startConditions()->create($dto->toArray());
        if (!$model) {
            throw new CreateException();
        }
        return $model;
    }

    /**
     * Подписан или нет указанный емаил
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function isExist(string $email): bool
    {
        return $this->startConditions()->where('email', $email)->exists();
    }
}