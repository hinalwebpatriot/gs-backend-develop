<?php


namespace GSD\Containers\Referral\Services;

use GSD\Containers\Referral\Data\Repositories\PromoCodeRepository;
use GSD\Containers\Referral\Data\Repositories\TransactionRepository;
use GSD\Containers\Referral\DTO\TransactionDTO;
use GSD\Containers\Referral\Exceptions\TransactionCreateException;
use GSD\Containers\Referral\Interfaces\PromoCodeInterface;
use GSD\Containers\Referral\Models\ReferralPromoCode;
use lenal\catalog\Models\Promocode;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * Class PromoCodeService
 * @package GSD\Containers\Referral
 */
class PromoCodeService
{
    private PromoCodeRepository   $repo;
    private TransactionRepository $transactionRepo;

    public function __construct(PromoCodeRepository $repo, TransactionRepository $transactionRepo)
    {
        $this->repo = $repo;
        $this->transactionRepo = $transactionRepo;
    }

    /**
     * Проверяет существует ли реферальный промокод
     *
     * @param  string  $code
     *
     * @return bool
     */
    public function checkOnExist(string $code): bool
    {
        return $this->repo->isExist($code);
    }

    /**
     * Возвращает примененный промокод к корзине
     * Если промокод не применен то NULL
     * @return string|null
     */
    public function getCodeFromCookie(): ?string
    {
        return request()->cookie('promocode');
    }

    /**
     * Возвращает интерфейс объекта промокода по коду
     * Обычные промокоды имеют приоритет над реферальными
     *
     * @param  string  $code
     *
     * @return PromoCodeInterface|null
     */
    public function getPromoCodeInterface(string $code): ?PromoCodeInterface
    {
        $promoCode = Promocode::findByCode($code);
        if (!$promoCode) {
            $promoCode = $this->repo->getByCode($code);
        }
        return $promoCode;
    }

    /**
     * Добавление реферальной скидки к сумме
     *
     * @param  float  $sum
     *
     * @return float
     */
    public function applyReferralDiscount(float $sum): float
    {
        $code = $this->getCodeFromCookie();
        if ($code) {
            $promo = $this->getPromoCodeInterface($code);
            if ($promo instanceof ReferralPromoCode) {
                return $sum - $this->adaptiveDiscountToCurrency($sum);
            }
        }
        return $sum;
    }

    /**
     * Расчет идет от суммы в австралийских долларах
     *
     * @param  float  $sum
     *
     * @return float
     */
    public function calculateDiscount(float $sum): float
    {
        if ($sum >= 3000 && $sum < 6000) {
            return 100;
        }
        if ($sum >= 6000 && $sum < 10000) {
            return 150;
        }
        if ($sum >= 10000 && $sum < 15000) {
            return 200;
        }
        if ($sum >= 15000 && $sum < 20000) {
            return 250;
        }
        if ($sum >= 20000) {
            return 300;
        }
        return 0;
    }

    /**
     * Создает транзакцию владельцу промокода от реферального ордера
     *
     * @param  ReferralPromoCode  $promoCode
     * @param  int|null           $order_id
     * @param  int|null           $tower_id
     * @param  float              $orderSum
     * @param  float              $discount
     *
     * @throws TransactionCreateException
     */
    public function makeTransaction(
        ReferralPromoCode $promoCode,
        ?int $order_id,
        ?int $tower_id,
        float $orderSum,
        float $discount
    ) {
        $dto = new TransactionDTO([
            'owner_id'  => $promoCode->owner_id,
            'code_id'   => $promoCode->id,
            'order_id'  => $order_id,
            'tower_id'  => $tower_id,
            'order_sum' => $orderSum,
            'payment'   => $discount
        ]);
        $this->transactionRepo->create($dto);
    }

    public function setUsed(ReferralPromoCode $promoCode)
    {
        $promoCode->is_used = true;
        $promoCode->save();
    }

    /**
     * Высчитывает дисконт в зависимости от валюты пользователя
     *
     * @TODO Нужно будет убрать зависимости от ленал компонентов при переписывании
     *
     * @param $sum
     *
     * @return float
     */
    public function adaptiveDiscountToCurrency($sum): float
    {
        $currency = CurrencyRate::getUserCurrency();
        if ($currency != 'AUD') {
            $sum = CurrencyRate::convert($sum, $currency, 'AUD');
        }
        $discount = $this->calculateDiscount($sum);
        if ($currency != 'AUD') {
            $discount = CurrencyRate::convert($discount, 'AUD', $currency);
        }
        return $discount;
    }
}