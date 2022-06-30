<?php


namespace GSD\Containers\Referral\Actions;


use GSD\Containers\Referral\DTO\SendRequestDTO;
use GSD\Containers\Referral\Jobs\SubscribeReferrerJob;
use GSD\Containers\Referral\Managers\ClientManager;
use GSD\Containers\Referral\Models\ReferralCustomer;
use GSD\Containers\Referral\Tasks\CheckInTowerTask;
use GSD\Containers\Referral\Tasks\GetCustomerTask;
use GSD\Containers\Referral\Tasks\MakeCustomerTask;
use GSD\Containers\Referral\Tasks\SendPromoCodeTask;
use GSD\Ship\Exceptions\NotFoundHttpException;
use GSD\Ship\Exceptions\UnprocessableEntityHttpException;
use GSD\Ship\Parents\Actions\Action;

/**
 * Class SendAction
 * @package GSD\Containers\Referral\Actions
 */
class SendAction extends Action
{
    /**
     * Отправляет промокоды от реферала
     *
     * @param  SendRequestDTO  $dto
     *
     * @return array
     */
    public function run(SendRequestDTO $dto): array
    {
        /** @var ReferralCustomer $referrer */
        $referrer = GetCustomerTask::runTask($dto->sender);

        if (!$referrer) {
            $referrer = MakeCustomerTask::runTask($dto->sender, $dto->sender_first_name, $dto->sender_last_name);
        }

        $referrer->first_name = $dto->sender_first_name;
        $referrer->last_name = $dto->sender_last_name;

        $result = [];
        foreach ($dto->recipients as $recipient) {
            try {
                SendPromoCodeTask::runTask($referrer, $recipient, $dto->comment);
                $result[] = [$recipient->email => 'sent'];
            } catch(UnprocessableEntityHttpException $e) {
                $result[] = [$recipient->email => $e->getMessage()];
            }
        }

        if ($dto->subscribe) {
            /** Регистрируем пользователя в подписке */
            SubscribeReferrerJob::dispatch($referrer->email);
        }

        return $result;
    }
}