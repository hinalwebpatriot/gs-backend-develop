<?php


namespace GSD\Containers\Referral\Tasks;


use GSD\Containers\Referral\Data\Repositories\PromoCodeRepository;
use GSD\Containers\Referral\DTO\RecipientDTO;
use GSD\Containers\Referral\Models\ReferralCustomer;
use GSD\Ship\Exceptions\UnprocessableEntityHttpException;
use GSD\Ship\Parents\Tasks\Task;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SendPromoCodeTask
 * @package GSD\Containers\Referral\Tasks
 */
class SendPromoCodeTask extends Task
{
    private PromoCodeRepository $promoCodeRepo;

    /**
     * SendPromoCodeTask constructor.
     *
     * @param  PromoCodeRepository  $promoCodeRepo
     */
    public function __construct(PromoCodeRepository $promoCodeRepo)
    {
        $this->promoCodeRepo = $promoCodeRepo;
    }

    /**
     * Создает реферала в базе
     *
     * @param  ReferralCustomer  $referrer
     * @param  RecipientDTO      $recipient
     * @param  string|null       $comment
     *
     * @return ReferralCustomer|Builder
     */
    public function run(ReferralCustomer $referrer, RecipientDTO $recipient, ?string $comment): void
    {
        $referrerCode = null;
        $codes = $this->promoCodeRepo->getByEmail($recipient->email);
        if ($codes->isNotEmpty()) {
            if ($codes->where('is_used', true)->count() > 0) {
                throw new UnprocessableEntityHttpException('The recipient has already used the code');
            }

            $referrerCode = $codes->where('owner_id', $referrer->id)->first();
        }
        if (!$referrerCode) {
            try {
                $referrerCode = $this->promoCodeRepo->create($referrer->id, $recipient);
            } catch (\Exception $e) {
                throw new UnprocessableEntityHttpException($e->getMessage());
            }
        }

        $referrerCode->sendPromoCodeToRecipient($referrer->first_name, $referrer->last_name, $comment);
    }
}