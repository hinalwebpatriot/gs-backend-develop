<?php


namespace GSD\Containers\Referral\Mails;


use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Ship\Parents\Mails\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Class PromoCodeMail
 * @package GSD\Containers\Referral\Mails
 */
class PromoCodeUsedMail extends Mail
{
    protected ReferralPromoCode $code;

    /**
     * PromoCodeMail constructor.
     *
     * @param  ReferralPromoCode  $code
     */
    public function __construct(ReferralPromoCode $code) {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = sprintf('%s %s', $this->code->owner->first_name, $this->code->owner->last_name);
        //TODO сделать замену лаяута из порто когда перенесу
        return $this->view('ReferralEmails::promo-code-used')
            ->subject('Your promo code has been used')
            ->with([
                'name' => $name,
                'imageStorage' => Storage::disk(config('filesystems.cloud')),
            ])->to($this->code->owner->email, $name);
    }
}