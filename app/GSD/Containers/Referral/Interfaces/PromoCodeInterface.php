<?php


namespace GSD\Containers\Referral\Interfaces;

/**
 * Интерфейс для объединения моделей промокодов
 *
 * Interface PromoCodeInterface
 * @package GSD\Containers\Referral\Interfaces
 */
interface PromoCodeInterface
{
    /**
     * Возвращает строку с промокодом
     * 
     * @return string
     */
    public function getCode(): string;

    /**
     * Возвращает доступность промокода
     *
     * @return bool
     */
    public function isRelevant(): bool;

    /**
     * Возвращает активность промокода
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Проверяет код подтверждения промокода
     *
     * @param $confirmCode
     *
     * @return bool
     */
    public function confirmation($confirmCode): bool;
}