<?php

namespace GSD\Containers\Referral\Components\GiftPay\Responses;

class SendByEmailResponse extends BaseResponse
{
    public string $statusCode;
    public string $message;
    public ?string $giftID;
    public ?string $expiry;
}