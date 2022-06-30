<?php


namespace GSD\Containers\Referral\Mails;


use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Ship\Parents\Mails\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Class PromoCodeMail
 * @package GSD\Containers\Referral\Mails
 */
class PromoCodeMail extends Mail
{
    protected ReferralPromoCode $code;
    private string              $sender_first_name;
    private string              $sender_last_name;
    private ?string             $comment;

    /**
     * PromoCodeMail constructor.
     *
     * @param  ReferralPromoCode  $code
     * @param  string             $sender_first_name
     * @param  string             $sender_last_name
     * @param  string|null        $comment
     */
    public function __construct(
        ReferralPromoCode $code,
        string $sender_first_name,
        string $sender_last_name,
        ?string $comment
    ) {
        $this->code = $code;
        $this->sender_first_name = $sender_first_name;
        $this->sender_last_name = $sender_last_name;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = sprintf('%s %s', $this->code->recipient_first_name, $this->code->recipient_last_name);
        $senderName = sprintf('%s %s', $this->sender_first_name, $this->sender_last_name);
        //TODO сделать замену лаяута из порто когда перенесу
        return $this->view('ReferralEmails::promo-code')
            ->subject(sprintf('%s just gave you a surprise gift at GS Diamonds!', $name))
            ->with([
                'code' => $this->code->code,
                'sender' => $senderName,
                'recipient' => $name,
                'comment' => $this->comment,
                'imageStorage' => Storage::disk(config('filesystems.cloud')),
            ])->to($this->code->recipient_email, $name);
    }
}