<?php

namespace lenal\catalog\Services\Payments;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use lenal\catalog\Models\Order;
use lenal\catalog\Services\OrderService;


class AdyenPayment
{
    private $merchantAccount;
    private $skin;
    private $hmacKey;
    private $webHookHmacKey;
    private $action = 'https://test.adyen.com/hpp/pay.shtml';
    private $checkoutBaseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->merchantAccount = config('adyen.merchantAccount');
        $this->skin = config('adyen.skin');
        $this->hmacKey = config('adyen.hmacKey');
        $this->webHookHmacKey = config('adyen.webHookHmacKey');
        $this->checkoutBaseUrl = config('adyen.checkoutBaseUrl');
        $this->apiKey = config('adyen.setXApiKey');
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $step
     * @param mixed $params
     */
    private function debug($step, $params)
    {
        logger()->channel('payment')->debug("Adyen debug: ($step)" . json_encode($params));
    }

    public function prepareFormParams(Order $order)
    {
        $productTitle = [];
        foreach ($order->cartItems as $cartItem) {
            $productTitle[] = $cartItem->product->title;
        }

        $params = [
            'merchantAccount' => (string) $this->merchantAccount,
            'skinCode' => $this->skin,
            'paymentAmount' => $this->formattedPrice($order->total_price),
            'currencyCode' => $order->currency,
            'merchantReference' => $order->uuid,
            'sessionValidity' => date('c', strtotime('24 hours')),
            'shipBeforeDate' => date('c', strtotime('10 days')),
            'shopperEmail' => $order->email,
            'resURL' => env('FRONTEND_URL').'/checkout/success/adyen?id=' . $order->uuid,
            'allowedMethods' => 'card',
        ];

        $params['merchantSig'] = $this->createSign($params);

        return $params;
    }

    public function buildPaymentUrl(Order $order)
    {
        $client = new Client([
            'base_uri' => $this->checkoutBaseUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'x-API-key' => $this->apiKey,
            ]
        ]);

        $items = [];
        $productTitle = [];
        foreach ($order->cartItems as $cartItem) {
            $items[] = [
                'id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
                'description' => $cartItem->product->title,
            ];
            $productTitle[] = $cartItem->product->title;
        }

        $params = [
            'allowedMethods' => ['card'],
            'merchantAccount' => (string) $this->merchantAccount,
            'reference' => $order->uuid,
            'returnUrl' => env('FRONTEND_URL').'/checkout/success/adyen?id=' . $order->uuid,
            'shopperEmail' => $order->email,
            'amount' => [
                'value' => $this->formattedPrice($order->total_price),
                'currency' => $order->currency,
            ],
            'lineItems' => $items,
            /*'deliveryAddress' => [
                'city' => $order->town_city,
                'country' => $order->country,
                'postalCode' => $order->zip_postal_code,
                'street' => $order->address,
            ],*/
        ];

        $response = $client->post('paymentLinks', [
            'json' => $params,
        ]);

        $res = json_decode($response->getBody()->getContents(), true);
        return $res['url'];
    }

    private function formattedPrice($amount)
    {
        return (int) $amount * 100;
    }

    private function createSign($params)
    {
        ksort($params, SORT_STRING);

        foreach ($params as $key => $value) {
            $params[$key] = str_replace(':','\\:', str_replace('\\', '\\\\', $value));
        }

        $sign = implode(":", array_merge(array_keys($params), array_values($params)));

        return $this->hashSign($sign, $this->hmacKey);
    }

    private function hashSign($sign, $hmacKey)
    {
        return base64_encode(hash_hmac('sha256', $sign, pack("H*" , $hmacKey), true));
    }

    /**
     * Webhook callback notification
     * @param array $params
     * @return string
     * @throws Exception
     */
    public function callback($params)
    {
        try {
            $result = $this->verify($params);

            if (!$result['success']) {
                $this->debug('not-verify', $params);
                exit;
            }

            $order = Order::findByUUID(Arr::get($result, 'uuid'));

            if (!$order || $order->total_price > $result['amount'] * 100) {
                $this->debug('no-order', $params);
                exit;
            }

            if ($this->formattedPrice($order->total_price) > $result['amount'] || strtolower($result['currency']) != strtolower($order->currency)) {
                $message = 'Expected amount: ' . $order->total_price . ' ' . $order->currency . '; ';
                $message .= 'Actual amount: ' . $result['amount']/100 . ' ' . $result['currency'];
                $this->debug('wrong-amount', $message);
            }

            $orderService = new OrderService($order);
            $orderService->process();
            return '[accepted]';
        } catch (\Exception $e) {
            $this->debug('web-hook-notification-error', array_merge($params, ['error' => $e->getMessage()]));
        }

        return '';
    }

    private function verify($notificationRequest)
    {
        foreach ( $notificationRequest["notificationItems"] as $notificationRequestItem ) {
            $params = $notificationRequestItem["NotificationRequestItem"];

            $payload = [
                $params['pspReference'],
                $params['originalReference'] ?? '',
                $params['merchantAccountCode'],
                $params['merchantReference'],
                $params['amount']['value'],
                $params['amount']['currency'],
                $params['eventCode'],
                $params['success']
            ];

            $calculatedHash = $this->hashSign(implode(':', $payload), $this->webHookHmacKey);

            if ($params['eventCode'] == 'AUTHORISATION' && $params['success'] === 'true' && strcmp($params['additionalData']['hmacSignature'], $calculatedHash) === 0) {
                return [
                    'success' => true,
                    'uuid' => $params['merchantReference'],
                    'currency' => $params['amount']['currency'],
                    'amount' => $params['amount']['value'],
                ];
            }
        }

        return [
            'success' => false,
        ];
    }
}