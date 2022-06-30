<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 3/18/19
 * Time: 2:45 PM
 */

namespace lenal\catalog\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use lenal\catalog\Facades\CartHelper;
use lenal\catalog\Facades\PaymentHelper;
use lenal\catalog\Models\Order;
use lenal\catalog\Requests\AlipayPaymentRequest;
use lenal\catalog\Requests\PaypalPaymentRequest;
use lenal\catalog\Requests\AdyenPaymentRequest;
use lenal\catalog\Requests\ProceedPaymentRequest;

use lenal\catalog\Services\Payments\AdyenPayment;
use PayPal\Api\Amount;
use PayPal\Api\Capture;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Exception;


class PaymentController
{
    public function paySystemsList()
    {
        return PaymentHelper::getPaySystems();
    }

    public function proceedPayment(ProceedPaymentRequest $request)
    {
        CartHelper::clearCart();
        return PaymentHelper::proceedPayment(request('order_id'), request('paysystem_id'))
            ->withCookie(cookie()->forever('cart', json_encode([])))
            ->withCookie(cookie()->forget('promocode'));
    }

    public function paypal(PaypalPaymentRequest $request)
    {
        if (request('paymentId') && request('PayerID')) {
            // paypal success -> execute payment
            return PaymentHelper::executePaypalPayment(request('paymentId'), request('PayerID'), request('token'));
        }
        // paypal failed - return order data by token
        return PaymentHelper::cancelPaypalPayment(request('token'));
    }

    public function alipay(AlipayPaymentRequest $request)
    {
        logger()->channel('payment')->debug($request->all());

        return PaymentHelper::executeAlipayPayment($request->all());
    }

    public function adyen(AdyenPaymentRequest $request)
    {
        return PaymentHelper::adyenPayment(request('value'), request('currency'),
            request('card'), request('order_id')
        );
    }

    public function adyenNotification(Request $request)
    {
        logger()->channel('payment')->debug(json_encode($request->all()));
        $payment = new AdyenPayment();

        return response($payment->callback($request->all()));
    }

    public function paypalTest()
    {

        //return view('catalog::files.invoice', ['order' => Order::find(1)]);
        return view('catalog::mail.order_payment_complete', ['order' => Order::find(1), 'payment_link' => 'sdfs']);
        // TODO remove this after all tests

        $paypal_conf = config('paypal');
        $api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $api_context->setConfig($paypal_conf['settings']);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();

        $item_1->setName('Item 1')
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(2.02);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(2.02);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description')
            //->setNotifyUrl("your notifyUrl here")
            //->setInvoiceNumber($invoiceNo)
        ;

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl('http://3.0.241.154:8080/')
        ->setCancelUrl('http://3.0.241.154:8080/');

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {

            $payment->create($api_context);

        } catch (Exception $e) {
            dd($e->getMessage());
        }


        dd();

        //dd($payment->getId()); //PAYID-LSKNDXA7SJ36973PY482305L
        //dd($payment);

        /*dump($payment->getApprovalLink());
        dump($payment->getId());*/

        /*$captureId='PAYID-LSKNPJI16610643SC4098459';
        try {
            $paymentData = Payment::get($captureId, $api_context);
        } catch (Exception $ex) {
            dump('Capture failed');
            dd($ex);
        }
        dump('EC-6FP564365B609971L');
        dd($paymentData);*/
        /*$params = array('cart' => '6FP564365B609971L');
        $payments = Payment::all($params, $api_context);
        dd($payments);*/

    }

    public function paypalNotify(Request $request)
    {
        Log::info($request->all());
    }

}