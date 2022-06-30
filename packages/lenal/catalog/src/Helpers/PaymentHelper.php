<?php

namespace lenal\catalog\Helpers;

use App\Helpers\Tools;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use lenal\catalog\DTO\OrderDetailsDTO;
use lenal\catalog\Mail\InvoiceMail;
use lenal\catalog\Mail\PaymentLinkMail;
use lenal\catalog\Models\Order;
use lenal\catalog\Models\Paysystem;
use lenal\catalog\Models\Status;
use lenal\catalog\Resources\OrderResource;
use lenal\catalog\Resources\PaysystemResource;
use lenal\catalog\Facades\OrderHelper;
use Barryvdh\DomPDF\Facade as PDF;
use lenal\catalog\Services\OrderService;
use lenal\catalog\Services\Payments\AdyenPayment;
use lenal\catalog\Services\Payments\AlipayPayment;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Carbon\Carbon;

class PaymentHelper
{

    protected static $success_status = [
            "AuthenticationFinished",
            "Authorised",
            "Received"
        ];

    protected static $err_status = [
        "Cancelled",
        "Error",
        "Refused"
    ];

    public function getPaySystems()
    {
        return PaysystemResource::collection(Paysystem::all());
    }

    public function setOrderPaySystem($orderId, $paySystemId)
    {
        Order::where('id', $orderId)->update(['paysystem_id' => $paySystemId]);
    }

    public function proceedPayment($orderId, $paySystemId)
    {
        $this->setOrderPaySystem($orderId, $paySystemId);
        /** @var Paysystem $paySystem */
        $paySystem = Paysystem::query()->where('id', $paySystemId)->first();
        $status = Status::query()->where('slug', $paySystem->slug)->first();

        /** @var Order $order */
        $order = Order::query()->where('id', $orderId)->first();
        $order->status = $status->id;

        if (!$order->discount_percent) {
            $order->discount_percent = $paySystem->discountPercent();
            if ($order->discount_percent) {
                $order->recalculate();
            }
        }

        $order->save();

        if ($order->isInvoice() && $order->invoice) {
            $order->invoice->paymentStatus();
        }

        if ($paySystem) {
            switch ($paySystem->slug) {
                case 'bank_transfer':
                    return $this->proceedBankInvoice($order);
                case 'paypal':
                    return $this->proceedPaypal($order);
                case 'adyen':
                    return $this->proceedAdyen($order);
                case 'alipay':
                    return $this->proceedAlipay($order);
            }
        }

        return OrderHelper::getOrder($orderId);

    }

    private function proceedBankInvoice(Order $order)
    {
        $orderDTO = OrderDetailsDTO::make($order);
        // generate pdf
        $filename = "invoices/invoice_$order->id.pdf";
        $pdf = PDF::loadView('catalog::files.invoice', [
            'order' => $orderDTO,
            'imageStorage' => Tools::defaultStorage()
        ]);
        Storage::disk('public')->put($filename, $pdf->output(), 'private');
        $filePath = public_path(Storage::url($filename));

        // send via email
        try {
            if ($order->email) {
                Mail::to($order->email)->send(new InvoiceMail($filePath, $orderDTO));
                Mail::to($this->getEmail())->send(new InvoiceMail($filePath, $orderDTO));
                Mail::to(config('app.team_mail'))->send(new InvoiceMail($filePath, $orderDTO));
            }
            $order
                ->clearMediaCollection('invoices')
                ->addMedia($filePath)
                ->withCustomProperties(['mime-type' => 'application/pdf'])
                ->toMediaCollection('invoices');
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
        return response(
            ['message' => trans('api.mail.default.success_send'), 'order' => new OrderResource($order)]
        );
    }

    public function proceedPaypal(Order $order)
    {
        $api_context = $this->getPaypalContext();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $cart = [];
        foreach ($order->cartItems as $cartItem) {
            $item = new Item();
            $item->setName($cartItem->product->title)
                ->setCurrency($order->currency)
                ->setQuantity(1)
                ->setPrice($cartItem->price);
            $cart[] = $item;
        }

        $item_list = new ItemList();
        $item_list->setItems($cart);

        $amount = new Amount();
        $amount->setCurrency($order->currency)
            ->setTotal($order->total_price);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            //->setDescription('Your transaction description')
            ->setNotifyUrl(env('FRONTEND_URL').route('paypal_notify', [], false))
            ->setInvoiceNumber($order->id); // TODO duplicate invoice number fix

        $redirect_urls = new RedirectUrls();
        $redirect_urls
            ->setReturnUrl(env('FRONTEND_URL').'/checkout/success/paypal')
            ->setCancelUrl(env('FRONTEND_URL').'/checkout/failure/paypal');

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($api_context);
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $redirect_url = $payment->getApprovalLink();

        // save token in case of cancel
        parse_str(parse_url($redirect_url)['query'], $paypalQuery);
        $order->update(['payment_token' => $paypalQuery['token']]);
        if ($order->email) {
            // send link via email
            Mail::to($order->email)->send(new PaymentLinkMail($order, $redirect_url));
            Mail::to($this->getEmail())->send(new PaymentLinkMail($order, $redirect_url));
            Mail::to(config('app.team_mail'))->send(new PaymentLinkMail($order, $redirect_url));
        }

        return response(['payment_url' => $redirect_url]);
    }

    public function proceedAlipay(Order $order)
    {
        $processor = $this->alipayProcessor();

        try {
            $paymentUrl = $processor->buildPaymentUrl($order);

            if ($order->email) {
                Mail::to($order->email)->send(new PaymentLinkMail($order, $paymentUrl));
                Mail::to($this->getEmail())->send(new PaymentLinkMail($order, $paymentUrl));
                Mail::to(config('app.team_mail'))->send(new PaymentLinkMail($order, $paymentUrl));
            }
        } catch (\Exception $e) {
            logger($e);
            return response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return response(['payment_url' => $paymentUrl]);
    }

    private function proceedAdyen(Order $order)
    {
        $payment = new AdyenPayment();
        $paymentUrl = [];
        $success = false;
        $error = '';

        try {
            $paymentUrl = $payment->buildPaymentUrl($order);

            if ($order->email) {
                Mail::to($order->email)->send(new PaymentLinkMail($order, $paymentUrl));
                Mail::to($this->getEmail())->send(new PaymentLinkMail($order, $paymentUrl));
                Mail::to(config('app.team_mail'))->send(new PaymentLinkMail($order, $paymentUrl));
            }
            $success = true;
        } catch (\Exception $e) {
            logger($e);
            $error = trans('api.prepare-form-fields-error');
        }

        return response()->json([
            'success' => $success,
            'error' => $error,
            'payment_url' => $paymentUrl,
            'submit_button' => trans('api.submit-pay'),
        ]);
    }

    public function executePaypalPayment($paymentId, $payerId, $token)
    {
        $api_context = $this->getPaypalContext();
        try{
            $payment = Payment::get($paymentId, $api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            // execute the payment
            $result = $payment->execute($execution, $api_context);
            if (!($result->getState() == 'approved')) {
                $order = $this->getPaypalOrderByToken($token);
                return response(
                    [
                        'message' => trans('api.paypal.execute_failed'),
                        'order' => new OrderResource($order)
                    ],
                    Response::HTTP_NOT_FOUND);
            }
            // set is_payed for order
            $orderId = (integer)last($result->getTransactions())->invoice_number;
            $order = Order::where('id', $orderId)->first();
            if (!$order) {
                return response(
                    [
                        'message' => trans('api.paypal.order_not_found'),
                        'order' => null
                    ],
                    Response::HTTP_NOT_FOUND);
            }

            (new OrderService($order))->afterPaid();

            return response(
                [
                    'message' => trans('api.paypal.execute_success'),
                    'order' => new OrderResource($order)
                ]
            );

        } catch (\Exception $e) {
            $exceptionData = [];
            $exceptionData['message'] = $e->getMessage();
            $exceptionData['code'] = $e->getCode();
            $exceptionData['file'] = $e->getFile();
            $exceptionData['line'] = $e->getLine();
            $exceptionData['trace'] = $e->getTraceAsString();
            //$exceptionData = json_decode($e->getData(), true);
            $exceptionData['source'] = 'paypal';
            $exceptionData['params'] = [
                'payment_id' => $paymentId,
                'payer_id' => $payerId
            ];
            Log::info($exceptionData);
            $order = $this->getPaypalOrderByToken($token);
            return response(
                [
                    'message' => trans('api.paypal.execute_failed'),
                    'order' => new OrderResource($order)
                ],
                Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param array $params
     * @return Response
     */
    public function executeAlipayPayment($params)
    {
        $processor = $this->alipayProcessor();
        /** @var Order $order */
        $order = Order::query()->where('uuid', Arr::get($params, 'merchant_trade_no'))->first();

        try {
            if (!$order) {
                throw new \Exception(trans('api.alipay.order_not_found'));
            }

            if ($order->isPayed()) {
                throw new \Exception(trans('api.order-already-payed'));
            }

            $response = $processor->callback($params);

            if (!$response['success']) {
                throw new \Exception('fail');
            }

            (new OrderService($order))->afterPaid();

            return response([
                'message' => trans('api.alipay.execute_success'),
                'order' => new OrderResource($order)
            ]);
        } catch (\Exception $e) {
            logger()->channel('payment')->debug($e);

            return response([
                'message' => trans('api.alipay.execute_failed'),
                'order' => new OrderResource($order)
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return AlipayPayment
     */
    private function alipayProcessor()
    {
        return app('alipay');
    }

    private function getPaypalContext()
    {
        $paypal_conf = config('paypal');
        $api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $api_context->setConfig($paypal_conf['settings']);
        return $api_context;
    }

    public function cancelPaypalPayment($token)
    {
        $order = $this->getPaypalOrderByToken($token);
        if (!$order) {
            return response(
                [ 'message' => trans('api.paypal.order_token_not_found') ],
                Response::HTTP_NOT_FOUND);
        }

        return response(
            [ 'order' => new OrderResource($order) ]
        );
    }

    private function getPaypalOrderByToken($token)
    {
        return Order::where('payment_token', $token)->first();
    }

    public function adyenPayment($value, $currency, $cart, $orderId)
    {
        $api_access = $this->getAccessAdyen();
        $client = new \Adyen\Client();
        $client->setApplicationName($api_access["setApplicationName"]);
        $client->setUsername($api_access["setUsername"]);
        $client->setPassword($api_access["setPassword"]);
        $client->setXApiKey($api_access["setXApiKey"]);
        $mode = $api_access["mode"];
        if ($mode == "TEST") {
             $client->setEnvironment(\Adyen\Environment::TEST);
        }
        if ($mode == "LIVE") {
             $client->setEnvironment(\Adyen\Environment::LIVE);
        }
        $service = new \Adyen\Service\Payment($client);

        $params["amount"]["value"] = (float)$value*100;
        $params["amount"]["currency"] = $currency;
        $params["reference"] = "order".$orderId;
        $params["returnUrl"] = $api_access["returnUrl"];
        $params["merchantAccount"] = $api_access["merchantAccount"];
        $params["additionalData"]["card.encrypted.json"] = $cart;

        try {
            $order = Order::where('id', $orderId)->first();

            if (!$order) {
                return response(
                    [
                        'message' => trans('api.adyen.order_not_found'),
                        'order' => null
                    ],
                    Response::HTTP_NOT_FOUND);
            }


            $add = $service->authorise($params);

            if (in_array($add["resultCode"], self::$success_status)) {
                (new OrderService($order))->afterPaid();

                return response(
                    [
                        'message' => trans('api.adyen.execute_success'),
                        'info'    => $add
                    ]);
            }
            if (in_array($add["resultCode"], self::$err_status)) {
                return response(
                    [
                        'message'       => trans('api.adyen.execute_failed'),
                        'info'          => $add,
                        'error_message' => trans('api.adyen.error_message2'),
                    ],
                    Response::HTTP_NOT_FOUND);
            }

            return response(
                [
                    'message' => trans('api.adyen.execute_failed'),
                    'info'    => $add,
                    'error_message' => trans('api.adyen.error_message'),
                ],
                Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return response(
                [
                    'message' => trans('api.adyen.execute_failed'),
                    'error_message' => $e->getMessage(),
                ],
                Response::HTTP_NOT_FOUND);
        }

    }

    private function getAccessAdyen()
    {
        $adyen_access = config('adyen');
        return $adyen_access;
    }

    private function getEmail() {
        return env('MAIL_SEND');
    }

}
