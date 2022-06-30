<?php


namespace GSD\Containers\Order\Data\Repositories;


use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use GSD\Containers\Order\Models\Order as Model;

/**
 * Class OrderRepository
 * @package GSD\Containers\Order\Data\Repositories
 */
class OrderRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * @param  int  $id
     *
     * @return Model|Builder|null
     */
    public function getById(int $id): ?Model
    {
        return $this->startConditions()->where('id', $id)->first();
    }
}