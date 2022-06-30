<?php

namespace lenal\catalog\Models;

use App\Helpers\Math;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use lenal\PriceCalculate\Facades\CurrencyRate;
use lenal\ShowRooms\Models\ShowRoom;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property mixed $total_price
 * @property string $first_name
 * @property string $last_name
 * @property mixed $tax
 * @property string $email
 * @property int $is_payed
 * @property int $uuid
 * @property string $currency
 * @property string $kind
 * @property string $promocode_id
 * @property mixed $discount_percent
 * @property mixed $status
 * @property int $referral_code_id
 * @property float $referral_discount
 * @property Carbon $delivered_at
 *
 * @property CartItem[] $cartItems
 * @property Invoice $invoice
 * @property Promocode $promocode
 * @property Paysystem $paySystem
 */
class Order extends Model implements HasMedia
{
    use InteractsWithMedia;

    const KIND_GENERAL = null;
    const KIND_SERVICE = 'service';

    protected $table = 'order';
    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone_number',
        'additional_phone_number',
        'address',
        'company_name',
        'town_city',
        'zip_postal_code',
        'country',
        'state',
        'first_name_home',
        'last_name_home',
        'phone_number_home',
        'add_phone_number_home',
        'address_home',
        'town_city_home',
        'zip_postal_code_home',
        'country_home',
        'state_home',
        'appartman_number_home',
        'billing_address',
        'special_package',
        'gift',
        'comment',
        'id_showroom',
        'total_price',
        "currency",
        'id_showroom',
        'paysystem_id',
        'order_id',
        'is_payed',
        'status',
        'payment_token',
        'kind',
        'promocode_id',
        'referral_code_id',
        'referral_discount',
        'delivered_at'
    ];

    protected $dates = [
        'delivered_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Order $order) {
            $order->uuid = (Str::uuid())->getHex();
        });

        static::deleting(function (Order $order) {
            $order->cartItems()->delete();
        });

    }

    /**
     * @param string $uuid
     * @return Order|Model|object|null
     */
    public static function findByUUID($uuid)
    {
        if (!$uuid) {
            return null;
        }

        return static::query()->where('uuid', $uuid)->first();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, "order_id");
    }


    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('invoices')
            ->useDisk(config('filesystems.cloud'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function showroom()
    {
        return $this->belongsTo(ShowRoom::class, 'id_showroom');
    }

    public function paySystem()
    {
        return $this->belongsTo(Paysystem::class, 'paysystem_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'promocode_id');
    }

    public function statusOrder()
    {
        return $this->belongsTo(Status::class, 'status');
    }


    public function getSubtotalAttribute()
    {
        return $this->total_price;
    }

    public function getShippingAttribute()
    {
        return 0;
    }

    public function getExcludingTaxAttribute()
    {
        return round($this->total_price - $this->tax, 2);
    }

    public function getIncludingTaxAttribute()
    {
        return $this->total_price;
    }

    public function getTaxAttribute()
    {
        return round($this->total_price - $this->total_price / 1.1, 2);
    }

    public function isPayed()
    {
        return $this->is_payed == 1;
    }

    public function isInvoice()
    {
        return $this->kind == self::KIND_SERVICE;
    }

    public function orderType()
    {
        return $this->kind;
    }

    public function recalculate()
    {
        if (!$this->discount_percent) {
            return;
        }

        switch ($this->kind) {
            case self::KIND_SERVICE:
                $this->total_price = $this->total_price - Math::percent($this->total_price, $this->discount_percent);
                break;
            default:
                $this->total_price = 0;
                foreach($this->cartItems as $cartItem) {
                    $discount = Math::percent($cartItem->price, $this->discount_percent);
                    $cartItem->price = $cartItem->price - $discount;
                    $cartItem->price_old = $cartItem->price + $discount;
                    $cartItem->save();

                    $this->total_price += $cartItem->price;
                }
        }
        if ($this->referral_discount) {
            $this->total_price -= $this->referral_discount;
        }
    }

    public function getDiscount()
    {
        $discount = 0;
        foreach ($this->cartItems as $item) {
            if ($item) {
                $discount += $item->discount();
            }
        }

        return $discount;
    }
}
