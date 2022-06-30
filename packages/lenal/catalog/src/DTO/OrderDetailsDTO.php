<?php


namespace lenal\catalog\DTO;


use Carbon\Carbon;
use Firebase\JWT\JWT;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Order;
use lenal\catalog\Services\DeliveryTimeService;

class OrderDetailsDTO
{
    public string $id;
    public Carbon $createdAt;

    public ?string $email;
    public ?string $firstName;
    public ?string $lastName;
    public ?string $phone;
    public ?string $phoneAdditional;
    public ?string $address;
    public ?string $townCity;
    public ?string $zip;
    public ?string $country;
    public ?string $state;

    public bool    $isShowroom;
    public ?string $showroomTitle;
    public ?string $showroomAddress;
    public ?string $showroomCountry;

    public string $paymentUrl;
    public ?string $paymentName;
    public string $deliveryTime;

    public ?float $subtotal;
    public ?float $discount;
    public ?float $referralDiscount;
    public ?float $subtotalWithDiscount;
    public ?float $gst;
    public ?float $shipping;
    public ?float $total;
    public string $currency;

    public bool    $hasPromoCode;
    public ?string $promoCode;

    /** @var OrderDetailsItemDTO[] */
    public array $items = [];

    public static function make(Order $order)
    {
        $dto = new static();
        $dto->id = $order->id;
        $dto->createdAt = new Carbon(strtotime($order->created_at));
        $dto->email = $order->email;
        $dto->firstName = $order->first_name;
        $dto->lastName = $order->last_name;
        $dto->phone = $order->phone_number;
        $dto->phoneAdditional = $order->additional_phone_number;

        $dto->address = $order->address;
        $dto->townCity = $order->town_city;
        $dto->zip = $order->zip_postal_code;
        $dto->country = $order->country;
        $dto->state = $order->state;

        $showroom = $order->showroom;
        $dto->isShowroom = !!$showroom;

        if ($showroom) {
            $dto->showroomTitle = $showroom->geo_title;
            $dto->showroomAddress = $showroom->address;
            $dto->showroomCountry = $showroom->country->title;
        }

        $hash = JWT::encode($order->id, config('catalog.order_secret_key'));;
        $dto->paymentUrl = config('app.frontend_url').'/checkout/order/'.$hash;

        $dto->paymentName = $order->paySystem->name ?? null;

        $products = $order->cartItems->map(function (CartItem $item) {
            return $item->product;
        });
        $deliveryPeriod = app(DeliveryTimeService::class)->maxForProductCollection($products);
        $deliveryParams = $deliveryPeriod->deliveryPeriodParams();
        switch ($deliveryParams['unit']) {
            case 'days':
                $unitText = $deliveryParams['period'] == 1 ? 'Day' : 'Days';
                $dto->deliveryTime = "{$deliveryParams['period']} Business {$unitText}";
                break;
            case 'weeks':
                $unitText = $deliveryParams['period'] == 1 ? 'Week' : 'Weeks';
                $dto->deliveryTime = "{$deliveryParams['period']} Business {$unitText}";
                break;
            default:
                $dto->deliveryTime = '';
        }

        $dto->subtotal = $order->subtotal - $order->tax;
        $dto->discount = $order->getDiscount();
        $dto->referralDiscount = $order->referral_discount;
        $dto->subtotalWithDiscount = $dto->subtotal + $dto->discount;
        $dto->gst = $order->tax;
        $dto->shipping = $order->shipping;
        $dto->total = $order->total_price;
        $dto->currency = $order->currency;

        $dto->hasPromoCode = !!$order->promocode;

        if ($dto->hasPromoCode) {
            $dto->promoCode = $order->promocode->code;
        }

        $order->cartItems->each(function (CartItem $cartItem) use (&$dto) {
            $item = OrderDetailsItemDTO::make($cartItem, $dto->currency);
            if ($item) {
                $dto->items[] = $item;
            }
        });

        return $dto;
    }
}