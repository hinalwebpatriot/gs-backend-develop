<?php


namespace GSD\Containers\Referral\Data\Repositories;


use Exception;
use GSD\Containers\Referral\DTO\RecipientDTO;
use GSD\Containers\Referral\Models\ReferralPromoCode as Model;
use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PromoCodeRepository
 * @package GSD\Containers\Referral\Data\Repositories
 */
class PromoCodeRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * @param  int           $referrer_id
     * @param  RecipientDTO  $recipient
     *
     * @return Model|Builder
     * @throws Exception
     */
    public function create(int $referrer_id, RecipientDTO $recipient): Model
    {
        $model = $this->startConditions()->create([
            'owner_id'             => $referrer_id,
            'recipient_email'      => $recipient->email,
            'recipient_first_name' => $recipient->first_name,
            'recipient_last_name'  => $recipient->last_name
        ]);
        if (!$model) {
            throw new Exception('Error create Promo code');
        }

        return $model;
    }

    /**
     * Проверяет существует ли такой код в базе
     *
     * @param  string  $code
     *
     * @return bool
     */
    public function isExist(string $code): bool
    {
        return $this->startConditions()->where('code', $code)->exists();
    }

    /**
     * Возвращает промокоды по емейлу получившего
     *
     * @param  string  $email
     *
     * @return Model[]|Collection
     */
    public function getByEmail(string $email): Collection
    {
        return $this->startConditions()->where('recipient_email', $email)->get();
    }

    /**
     * Возвращает промокод по его коду
     *
     * @param  string  $code
     *
     * @return Model|Builder|null
     */
    public function getByCode(string $code)
    {
        return $this->startConditions()->where('code', $code)->first();
    }
}