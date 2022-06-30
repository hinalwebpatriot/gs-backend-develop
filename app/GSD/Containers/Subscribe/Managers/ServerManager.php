<?php


namespace GSD\Containers\Subscribe\Managers;


use GSD\Containers\Subscribe\DTO\SubscriberDTO;
use GSD\Containers\Subscribe\Enums\GenderEnum;
use GSD\Containers\Subscribe\Enums\TypeEnum;
use GSD\Containers\Subscribe\Tasks\ExistTask;
use GSD\Containers\Subscribe\Tasks\SubscribeTask;
use GSD\Ship\Parents\Managers\ServerManager as ParentServerManager;

/**
 * Class ServerManager
 * @package GSD\Containers\Subscribe\Managers
 */
class ServerManager extends ParentServerManager
{
    public static function subscribeForReferral(string $email)
    {
        if (!ExistTask::runTask($email)) {
            $dto = new SubscriberDTO([
                'email' => $email,
                'type' => array_values(TypeEnum::values()),
                'gender' => GenderEnum::REFERRER()->getValue(),
            ]);
            SubscribeTask::runTask($dto);
        }
    }
}