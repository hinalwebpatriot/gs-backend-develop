<?php


namespace GSD\Containers\Referral\Actions;


use GSD\Containers\Referral\Tasks\CheckInCustomersTask;
use GSD\Containers\Referral\Tasks\CheckInTowerTask;
use GSD\Ship\Parents\Actions\Action;

/**
 * Class CheckAction
 * @package GSD\Containers\Referral\Actions
 */
class CheckAction extends Action
{
    /**
     * Проверяет разрешено ли данному емейлу отправлять промокоды
     *
     * @param  string  $email
     *
     * @return bool
     */
    public function run(string $email): bool
    {
        return true;
    }
}