<?php


namespace GSD\Containers\Referral\Data\Repositories;


use GSD\Containers\Referral\Models\TowerCustomer as Model;
use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TowerCustomerRepository
 * @package GSD\Containers\Referral\Data\Repositories
 */
class TowerCustomerRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * Проверяет существует ли клиент с таким емейлом.
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function existByEmail(string $email): bool
    {
        return $this->startConditions()->where('email', $email)->exists();
    }

    /**
     * Возвращает клиента по емейлу.
     *
     * @param  string  $email
     *
     * @return Model|Builder|null
     */
    public function getByEmail(string $email): ?Model
    {
        return $this->startConditions()->where('email', $email)->first();
    }
}