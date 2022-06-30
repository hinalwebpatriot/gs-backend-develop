<?php

namespace lenal\promo_registration\Services;


use Illuminate\Support\{Arr, Str, Facades\Mail};
use lenal\catalog\Mail\PromocodeMail;
use lenal\catalog\Mail\ServiceInvoiceMail;
use lenal\catalog\Models\Promocode;
use lenal\promo_registration\Mail\PromoRegisterConfirmation;
use lenal\promo_registration\Models\PromoRegistration;
use lenal\promo_registration\Models\PromoRegistrationText;
use lenal\promo_registration\Repositories\PromoRegistrationRepository;

class PromoRegistrationService
{
    const MAIL_EXPIRE_MINUTES = 3;
    const CODE_KEY = 'promoRegisterCode';

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function param($key)
    {
        return Arr::get(session(self::CODE_KEY), $key);
    }

    public static function validateCode($email, $code)
    {
        return static::param('email') === $email && static::param('code') === $code;
    }

    public function sendConfirmCode()
    {
        $code = Str::lower(Str::random(6));

        session()->put(self::CODE_KEY, [
            'code' => $code,
            'expire' => strtotime('+' . self::MAIL_EXPIRE_MINUTES . ' minutes'),
            'email' => $this->data['email']
        ]);

        Mail::to($this->data['email'])->send(new PromoRegisterConfirmation($code));
    }

    public function register()
    {
        $model = new PromoRegistration();
        $model->fill($this->data);

        if ($model->save()) {
            $this->createPromoCode($model->email);
        }

        session()->remove(self::CODE_KEY);
    }

    public function createPromoCode($email)
    {
        $promoSettings = (new PromoRegistrationRepository())->firstActive();
        if (!$promoSettings || !$promoSettings->hasDiscount()) {
            return ;
        }

        /** @var Promocode $promo */
        $promo = Promocode::query()->create([
            'kind' => Promocode::KIND_PERSONAL,
            'personal_email' => $email,
            'discount' => $promoSettings->discount_percent,
            'discount_value' => $promoSettings->discount_value,
        ]);

        Mail::to($email)->send(new PromocodeMail($promo));
    }
}