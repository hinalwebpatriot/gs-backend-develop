<?php


namespace GSD\Containers\Referral\Data\Repositories;


use Exception;
use GSD\Containers\Referral\DTO\CustomerDTO;
use GSD\Containers\Referral\Models\ReferralCustomer as Model;
use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CustomerRepository
 * @package GSD\Containers\Referral\Data\Repositories
 */
class CustomerRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * @param  CustomerDTO  $customer
     *
     * @return Model|Builder
     * @throws Exception
     */
    public function create(CustomerDTO $customer): Model
    {
        $model = $this->startConditions()->create($customer->toArray());
        if (!$model) {
            throw new Exception('Error create Customer');
        }

        return $model;
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

    /**
     * Возвращает клиента по идентификатору
     *
     * @param  int  $id
     *
     * @return Model|Builder|null
     */
    public function getById(int $id): ?Model
    {
        return $this->startConditions()->where('id', $id)->first();
    }
}