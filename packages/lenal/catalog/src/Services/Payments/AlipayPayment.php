<?php

namespace lenal\catalog\Services\Payments;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use lenal\catalog\Models\Order;
use lenal\PriceCalculate\Facades\CurrencyRate;

class AlipayPayment
{
    private $test = true;
    /**
     * @var Client
     */
    private $currency = 'AUD';
    private $client;
    private $merchantId;
    private $authenticationCode;

    public function __construct($merchantId, $authenticationCode)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.superpayglobal.com'
        ]);

        $this->merchantId = $merchantId;
        $this->authenticationCode = $authenticationCode;
    }

    /**
     * @param string $step
     * @param string $params
     */
    private function debug($step, $params)
    {
        logger()->channel('payment')->debug("Alipay debug: ($step)" . json_encode($params));
    }

    public function callback($params)
    {
        $this->debug('callback begin', $params);

        $noticeId = Arr::get($params, 'notice_id');
        $merchantTradeNo = Arr::get($params, 'merchant_trade_no');

        $token = $this->createRequestToken([
            'notice_id' => $noticeId,
            'merchant_trade_no' => $merchantTradeNo,
            'authentication_code' => $this->authenticationCode,
        ]);

        if ($token !== Arr::get($params, 'token')) {
            $this->debug('invalid sign', $token);
            throw new Exception(trans('api.alipay.token_failed'));
        }

        try {
            $verificationToken = $this->createRequestToken([
                'notice_id' => $noticeId,
                'merchant_trade_no' => $merchantTradeNo,
                'authentication_code' => $this->authenticationCode
            ]);

            $this->debug('verificationToken', $verificationToken);

            $response = $this->client->get('payment/bridge/notification_verification', [
                'query' => [
                    'notice_id' => $noticeId,
                    'merchant_trade_no' => $merchantTradeNo,
                    'token' => $verificationToken
                ],
            ])->getBody()->getContents();

            $this->debug('notification_verification', json_encode($response));

            return [
                'success' => $response === 'SUCCESS'
            ];
        } catch (\Exception $e) {
            $this->debug('notification_verification_exception', $e->getMessage());
            return [
                'success' => false
            ];
        }
    }

    public function buildPaymentUrl(Order $order)
    {
        $productTitle = [];

        if ($order->isInvoice() && $order->invoice) {
            foreach ($order->invoice->services as $service) {
                $productTitle[] = $service->title;
            }
        } else {
            foreach ($order->cartItems as $cartItem) {
                $productTitle[] = $cartItem->product->title;
            }
        }


        $params = [
            'merchant_id' => (string) $this->merchantId,
            'product_title' => implode('; ', $productTitle),
            'total_amount' => (string) ceil(CurrencyRate::convert($order->total_price, $order->currency, $this->currency)),
            'merchant_trade_no' => (string) $order->uuid,
            'notification_url' => url('api/payment/alipay'),
            'currency' => $this->currency,
            'create_time' => gmdate ('Y-m-d H:i:s'),
            'return_url' => env('FRONTEND_URL').'/checkout/success/alipay?id=' . $order->uuid,
        ];

        $params['token'] = $this->createToken($params['merchant_trade_no'], $params['total_amount']);

        return 'https://api.superpayglobal.com/payment/bridge/merchant_request?' . http_build_query($params);
    }

    private function createToken($merchantTradeNo, $totalAmount)
    {
        return $this->createRequestToken([
            'merchant_id' => $this->merchantId,
            'authentication_code' => $this->authenticationCode,
            'merchant_trade_no' => $merchantTradeNo,
            'total_amount' => $totalAmount,
        ]);
    }

    public function createRequestToken($params)
    {
        return md5(http_build_query($params));
    }
}