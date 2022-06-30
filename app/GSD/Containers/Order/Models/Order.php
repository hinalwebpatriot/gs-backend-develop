<?php


namespace GSD\Containers\Order\Models;


use App\Helpers\Math;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Support\Str;
use lenal\catalog\Models\CartItem;
use lenal\catalog\Models\Invoice;
use lenal\catalog\Models\Paysystem;
use lenal\catalog\Models\Promocode;
use lenal\catalog\Models\Status;
use lenal\ShowRooms\Models\ShowRoom;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @TODO Скопипастил модель и ничего не менял, нужно было для репозитория
 * Class Order
 * @package GSD\Containers\Order\Models
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
        'delivered_at'
    ];

    protected $dates = [
        'delivered_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (\lenal\catalog\Models\Order $order) {
            $order->uuid = (Str::uuid())->getHex();
        });

        static::deleting(function (Order $order) {
            $order->cartItems()->delete();
        });

    }

    /**
     * @param string $uuid
     *
     * @return Order|\Illuminate\Database\Eloquent\Model|object|null
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

        $this->total_price = 0;
        foreach($this->cartItems as $cartItem) {
            $discount = Math::percent($cartItem->price, $this->discount_percent);
            $cartItem->price_old += $discount;
            $cartItem->price -= $discount;
            $cartItem->save();

            $this->total_price += $cartItem->price;
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