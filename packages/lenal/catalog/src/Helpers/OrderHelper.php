<?php

namespace lenal\catalog\Helpers;

use GSD\Containers\Referral\Models\ReferralPromoCode;
use GSD\Containers\Referral\Services\PromoCodeService as ReferralPromoCodeService;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use lenal\catalog\DTO\OrderDetailsDTO;
use lenal\catalog\Mail\OrderDetailsMail;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Invoice;
use lenal\catalog\Models\Order;
use lenal\catalog\Models\Paysystem;
use lenal\catalog\Models\Promocode;
use lenal\catalog\Models\Status;
use lenal\catalog\Facades\CartHelper;
use lenal\catalog\Resources\CartItemsCollection;
use lenal\catalog\Resources\OrderResource;
use lenal\catalog\Resources\ProductResource;
use lenal\catalog\Services\Promocode\PromocodeService;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\catalog\Facades\CommonHelper as CommonHelper;

class OrderHelper
{

    public function createOrder($request)
    {
        $cartItems = CartHelper::getCart();
        $sum = $cartItems->map(function ($cartItems) use ($request) {
            $resource = (new ProductResource($cartItems))->toArray($request);
            return $resource['price']['count'];
        })
            ->sum();
        $currency = CurrencyRate::getUserCurrency();

        if (!$cartItems->collection->count() > 0) {
            return response(
                ['message' => trans('api.order.error.cart_is_empty')],
                Response::HTTP_NOT_FOUND);
        }
        $special_package = 1;
        $billing_address = 1;
        $office = $request->Office;
        if ($office && array_key_exists("billing_address", $office)) {
            $billing_address = $office["billing_address"];
        }
        if ($office && array_key_exists("special_package", $office)) {
            $special_package = $office["special_package"];
        }
        $status = Status::where("slug", "prossesing")->first();

        $promocodeService = app(PromocodeService::class);
        $promocode = $promocodeService->restorePromocode();

        /** @var ReferralPromoCodeService $promoService */
        $promoService = app(ReferralPromoCodeService::class);

        $discount = null;
        $audDiscount = null;
        $orderSum = $sum;

        if ($promocode instanceof ReferralPromoCode) {
            if ($currency != 'AUD') {
                $orderSum = CurrencyRate::convert($orderSum, $currency, 'AUD');
            }
            $audDiscount = $promoService->calculateDiscount($orderSum);
            $discount = $audDiscount;
            if ($currency != 'AUD') {
                $discount = CurrencyRate::convert($discount, 'AUD', $currency);
            }
            $sum -= $discount;
        }

        $order = Order::create([
            'email'                   => Arr::get($request->Shared, "email"),
            'first_name'              => Arr::get($request->Shared, "first_name"),
            'last_name'               => Arr::get($request->Shared, "last_name"),
            'phone_number'            => Arr::get($request->Shared, "phone_number"),
            'additional_phone_number' => Arr::get($request->Shared, "additional_phone_number"),
            'address'                 => Arr::get($request->Office, "address"),
            'company_name'            => Arr::get($request->Office, "company_name"),
            'town_city'               => Arr::get($request->Office, "town_city"),
            'zip_postal_code'         => Arr::get($request->Office, "zip_postal_code"),
            'country'                 => Arr::get($request->Office, "country"),
            'state'                   => Arr::get($request->Office, "state"),
            'first_name_home'         => Arr::get($request->Home, "first_name"),
            'last_name_home'          => Arr::get($request->Home, "last_name"),
            'phone_number_home'       => Arr::get($request->Home, "phone_number"),
            'add_phone_number_home'   => Arr::get($request->Home, "additional_phone_number"),
            'address_home'            => Arr::get($request->Home, "address"),
            'town_city_home'          => Arr::get($request->Home, "town_city"),
            'zip_postal_code_home'    => Arr::get($request->Home, "zip_postal_code"),
            'country_home'            => Arr::get($request->Home, "country"),
            'state_home'              => Arr::get($request->Home, "state"),
            'appartman_number_home'   => Arr::get($request->Home, "appartman_number"),
            'billing_address'         => $billing_address,
            'special_package'         => $special_package,
            'gift'                    => Arr::get($request->Shared, "gift"),
            'comment'                 => Arr::get($request->Shared, "comment"),
            'id_showroom'             => Arr::get($request->Showroom, "id_showroom"),
            'total_price'             => $sum,
            'status'                  => $status->id,
            'currency'                => $currency,
            'promocode_id'            => $promocode instanceof Promocode ? $promocode->id : null,
            'referral_code_id'        => $promocode instanceof ReferralPromoCode ? $promocode->id : null,
            'referral_discount'       => $discount,
        ]);

        if ($audDiscount > 0 && $promocode instanceof ReferralPromoCode) {
            try {
                $promoService->makeTransaction($promocode, $order->id, null, $orderSum, $audDiscount);
                $promoService->setUsed($promocode);
            } catch (\Throwable $e) {
                Log::error($e);
            }
        }

        if ($user = Auth::guard('api')->user()) {
            foreach ($cartItems as $arItem) {
                CartItem::query()
                    ->where([['order_id', null], ['user_id', $user->id], ['product_id', $arItem->id]])
                    ->get()
                    ->each(function (CartItem $item) use ($order, $arItem) {
                        $arrayProduct = $item->toArray();
                        unset($arrayProduct['id']);
                        $product = new CartItem($arrayProduct);
                        $product->price = $arItem->calculated_price;
                        $product->order_id = $order->id;
                        $product->price_old = $arItem->promocode['price_old'] ?? null;
                        $product->save();
                    });
            }

            // mail order details
            $this->sendOrderDetails($order);

            return response([
                'message' => trans('api.order.added'),
                "id"      => $order->id,
                'uuid'    => $order->uuid,
            ]);
            //->withCookie($promocodeService->clearCookie());
        }

        $cookieCart = CartHelper::getCartItemsFromCookie();
        foreach ($cookieCart as $item_cart) {
            $this->addItemToUserCart($item_cart, $order->id);
        }

        /*$cart = CommonHelper::getSavedItemsFromCookie('cart');
        foreach ($cart as $id => $cartItem) {
            unset($cart[$id]);
        }*/

        $this->sendOrderDetails($order);

        return response([
            'message' => trans('api.order.added_cart'),
            "id"      => $order->id,
            'uuid'    => $order->uuid
        ]);
            //->withCookie(cookie()->forever('cart', json_encode($cart)))
            //->withCookie($promocodeService->clearCookie());
    }

    public function createOrderPaypal($request = null)
    {
        $cartItems = CartHelper::getCart();
        //dd($cartItems);
        $sum = $cartItems->map(function ($cartItems) use ($request) {
            $resource = (new ProductResource($cartItems))->toArray($request);
            return $resource['price']['count'];
        })
            ->sum();
        $currency = CurrencyRate::getUserCurrency();
        if (!$cartItems->collection->count() > 0) {
            return response(
                ['message' => trans('api.order.error.cart_is_empty')],
                Response::HTTP_NOT_FOUND);
        }
        $pay_system = Paysystem::where('slug', 'paypal')->first();
        $status = Status::where('slug', 'paypal')->first();
        $order = Order::create([
            'paysystem_id' => $pay_system->id,
            'total_price'  => $sum,
            'status'       => $status->id,
            'currency'     => $currency,
        ]);

        if ($user = Auth::guard('api')->user()) {
            foreach ($cartItems as $arItem) {
                $cart = CartItem::where([['order_id', null], ['user_id', $user->id], ['product_id', $arItem->id]])
                    ->get()
                    ->each(function ($item) use ($order, $arItem) {
                        $item->price = $arItem->calculated_price;
                        $item->order_id = $order->id;
                        $item->save();
                    });
            }

            // mail order details
            $this->sendOrderDetails($order);

            return response([
                'message'      => trans('api.order.added'),
                "id"           => $order->id,
                'uuid'         => $order->uuid,
                "paysystem_id" => $pay_system->id
            ]);
        }

        $cookieCart = CartHelper::getCartItemsFromCookie();
        foreach ($cookieCart as $item_cart) {
            $this->addItemToUserCart($item_cart, $order->id);
        }

        $cart = CommonHelper::getSavedItemsFromCookie('cart');
        foreach ($cart as $id => $cartItem) {
            unset($cart[$id]);
        }

        $this->sendOrderDetails($order);

        return response([
            'message'      => trans('api.order.added_cart'),
            "id"           => $order->id,
            'uuid'         => $order->uuid,
            "paysystem_id" => $pay_system->id
        ])
            ->withCookie(
                cookie()->forever('cart', json_encode($cart))
            );
    }

    public function createInvoiceOrder($invoiceId)
    {
        /** @var Invoice $invoice */
        $invoice = Invoice::query()->with('services')->where('id', $invoiceId)->first();

        if (!$invoice || !$invoice->isActive()) {
            return response([
                'message' => trans('api.invoice-not-available')
            ], Response::HTTP_NOT_FOUND);
        }

        $currency = CurrencyRate::getUserCurrency();

        $status = Status::findBySlug(Status::STATUS_PROCESS);

        $promocodeService = app(PromocodeService::class);
        $promocode = $promocodeService->restorePromocode();

        /** @var ReferralPromoCodeService $promoService */
        $promoService = app(ReferralPromoCodeService::class);

        $sum = $invoice->convertedIncPrice();

        $discount = 0;
        $audDiscount = 0;
        $orderSum = $sum;

        if ($promocode instanceof ReferralPromoCode) {
            if ($currency != 'AUD') {
                $orderSum = CurrencyRate::convert($orderSum, $currency, 'AUD');
            }
            $audDiscount = $promoService->calculateDiscount($orderSum);
            $discount = $audDiscount;
            if ($currency != 'AUD') {
                $discount = CurrencyRate::convert($discount, 'AUD', $currency);
            }
            $sum -= $discount;
        }

        /** @var Order $order */
        $order = Order::query()->create([
            'email'             => $invoice->email,
            'first_name'        => $invoice->email,
            'last_name'         => '',
            'phone_number'      => '',
            'billing_address'   => '',
            'special_package'   => '',
            'total_price'       => $sum,
            'status'            => $status->id,
            'currency'          => $currency,
            'kind'              => Order::KIND_SERVICE,
            'referral_code_id'  => $promocode instanceof ReferralPromoCode ? $promocode->id : null,
            'referral_discount' => $discount,
        ]);

        if ($promocode instanceof ReferralPromoCode && $audDiscount > 0) {
            try {
                $promoService->makeTransaction($promocode, $order->id, null, $orderSum, $audDiscount);
                $promoService->setUsed($promocode);
            } catch (\Throwable $e) {
                Log::error($e);
            }
        }

        if ($user = Auth::guard('api')->user()) {
            $invoice->user_id = $user->id;
        }

        $invoice->order_id = $order->id;
        $invoice->orderStatus();
        $invoice->save();

        return response(['message' => trans('api.order-invoice-created'), 'id' => $order->id, 'uuid' => $order->uuid]);
    }

    public function getOrder($id)
    {
        $order = $this->getOrderInfo($id);
        return new OrderResource($order);
    }

    public function getOrderByToken($token)
    {
        $order = Order::query()
            ->where(['payment_token' => $token])
            ->first();

        return new OrderResource($order);
    }

    private function getCartItems($id)
    {
        return CartItem
            ::where(['order_id' => $id])
            ->get()
            ->map(function ($cartItem) {
                return $cartItem->product_type::withCalculatedPrice()->where('id', $cartItem->product_id)->first();
            });
    }

    private function getOrderInfo($id)
    {
        $column = is_numeric($id) ? 'id' : 'uuid';

        return Order::query()
            ->where([$column => $id])
            ->first();
    }

    private function addItemToUserCart($item, $order_id)
    {
        if ($item != null) {
            $cart = new CartItem();
            $cart->order_id = $order_id;
            $cart->size_slug = $item->size_slug;
            $cart->price = $item->calculated_price;
            $cart->engraving = $item->engraving;
            $cart->engraving_font = $item->engraving_font;
            $cart->price_old = $item->promocode['price_old'] ?? null;

            $cart->save();
            $item->carts()->save($cart);
        }
    }

    private function sendOrderDetails($order)
    {
        try {
            $orderDTO = OrderDetailsDTO::make($order);
            Mail::to($order->email)->send(new OrderDetailsMail($orderDTO));
            if (config('app.team_mail')) {
                Mail::to(config('app.team_mail'))->send(new OrderDetailsMail($orderDTO));
            }

            if (config('app.sale_mail')) {
                Mail::to(config('app.sale_mail'))->send(new OrderDetailsMail($orderDTO));
            }
        } catch (\Exception $e) {
            Log::info($order->toArray());
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
