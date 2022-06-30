<?php

namespace GSD\Containers\Referral\Components\GiftPay;

use GSD\Containers\Referral\Components\GiftPay\Exceptions\AttributeNotValidateException;
use GSD\Containers\Referral\Components\GiftPay\Exceptions\ResponseErrorException;
use GSD\Containers\Referral\Components\GiftPay\Responses\SendByEmailResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GiftPay
{
    const STATUS_CODE_OK = 1;

    private bool   $sandbox;
    private string $apiKey;
    private string $uriApi        = 'https://express.giftpay.com/api/gift.svc/send';
    private string $sandboxUriApi = 'https://sandbox.express.giftpay.com/api/gift.svc/send';
    private array  $errors        = [
        '-1'  => 'Authorisation failed',
        '-2'  => 'Reserved',
        '-3'  => 'Reserved',
        '-4'  => 'Program gift credit exhausted',
        '-5'  => 'Program daily limit would be exceeded',
        '-6'  => 'Duplicate clientref while value and/or recipient do not match',
        '-7'  => 'The gift ID is invalid, or the gift ID does not exist, or the gift has already been cancelled.',
        '-8'  => 'Failed The email address does not match the gift ID.',
        '-9'  => 'Failed Invalid parameter',
        '-10' => 'Failed The API key type is different to the API call type'
    ];

    public function __construct(bool $sandbox, string $apiKey)
    {
        $this->sandbox = $sandbox;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws AttributeNotValidateException
     * @throws ResponseErrorException
     */
    public function sendByEmail(int $transactionId, string $from, string $to, float $amount, string $message): SendByEmailResponse
    {
        if (($amount < 5 || $amount > 100) && $amount % 5 > 0) {
            throw new AttributeNotValidateException('Gift card amount must be multiple of 5. Min 5, max 1000.');
        }
        $response = Http::get($this->sandbox ? $this->sandboxUriApi : $this->uriApi, [
            'key'       => $this->apiKey,
            'to'        => $to,
            'value'     => $amount,
            'clientref' => $transactionId,
            'from'      => $from,
            'message'   => $message
        ]);
        if ($response->status() !== Response::HTTP_OK) {
            throw new ResponseErrorException('GiftPay Error');
        }
        $result = new SendByEmailResponse($response->body());
        Log::debug($response->body());
        if ($result->statusCode != self::STATUS_CODE_OK) {
            throw new ResponseErrorException($result->message);
        }
        return $result;
    }
}