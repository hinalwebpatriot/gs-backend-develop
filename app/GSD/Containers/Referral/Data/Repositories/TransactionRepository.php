<?php


namespace GSD\Containers\Referral\Data\Repositories;


use Exception;
use GSD\Containers\Referral\DTO\RecipientDTO;
use GSD\Containers\Referral\DTO\TransactionDTO;
use GSD\Containers\Referral\Exceptions\TransactionCreateException;
use GSD\Containers\Referral\Models\ReferralTransaction;
use GSD\Containers\Referral\Models\ReferralTransaction as Model;
use GSD\Ship\Exceptions\NotFoundHttpException;
use GSD\Ship\Parents\Repositories\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionRepository
 * @package GSD\Containers\Referral\Data\Repositories
 */
class TransactionRepository extends Repository
{

    /**
     * @inheritDoc
     */
    public function modelName(): string
    {
        return Model::class;
    }

    /**
     * Создает транзакцию по ордеру для владельца реферального промокода
     *
     * @param  TransactionDTO  $dto
     *
     * @return Model|Builder
     * @throws TransactionCreateException
     */
    public function create(TransactionDTO $dto): Model
    {
        $model = $this->startConditions()->create($dto->toArray());
        if (!$model) {
            throw new TransactionCreateException();
        }

        return $model;
    }

    /**
     * Возвращает идентификаторы транзакции и заказов по которым не завершены по истечении определенного времени
     *
     * @return array
     */
    public function getDataNotApproved(): array
    {
        $data =  $this->startConditions()
            ->whereNull('approved_at')
            ->whereNotNull('order_id')
            ->where(
                DB::raw('DATE_FORMAT(created_at, \'%Y-%m-%d\')'),
                '<',
                now()->subDays(config('referral.main.checkOldTransactionsDays'))->format('Y-m-d')
            )
            ->select(['id', 'order_id'])
            ->get()
            ->mapWithKeys(function (ReferralTransaction $item) {
                return [$item->id => $item->order_id];
            })
            ->toArray();
        return $data;
    }

    /**
     * Удаляет транзакцию
     *
     * @param  int  $id  идентификатор транзакции
     *
     * @return bool|mixed|null
     */
    public function delete(int $id): ?bool
    {
        return $this->startConditions()->where('id', $id)->delete();
    }

    /**
     * Подтверждает транзакцию
     *
     * @param  int  $id
     *
     * @throws \Throwable
     */
    public function approve(int $id)
    {
        /** @var ReferralTransaction $model */
        $model = $this->startConditions()->where('id', $id)->first();
        if (!$model) {
            throw new NotFoundHttpException('Transaction not found');
        }
        $model->approved_at = now();
        $model->saveOrFail();
    }
}