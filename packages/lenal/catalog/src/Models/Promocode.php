<?php

namespace lenal\catalog\Models;

use App\Helpers\Math;
use GSD\Containers\Referral\Interfaces\PromoCodeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use lenal\catalog\Models\Products\Product;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * @property integer $id
 * @property string $code
 * @property string $kind
 * @property float $discount
 * @property \Carbon\Carbon $validity_date
 * @property string $personal_email
 * @property string $confirm_code
 * @property integer $max_times
 * @property integer $used_times
 * @property string|array $category
 * @property string $products_sku
 * @property string|array $collections
 * @property string|array $brands
 * @property integer $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property array $sku_list
 * @property int $discount_value
 */
class Promocode extends Model implements PromoCodeInterface
{
    const KIND_PERSONAL = 'personal';
    const KIND_GROUP = 'group';

    protected $table = 'promocodes';
    protected $guarded = [];
    protected $dates = ['validity_date'];
    protected $casts = [
        'validity_date' => 'datetime',
        'collections' => 'array',
        'brands' => 'array',
        'category' => 'array',
    ];
    protected $attributes = [
        'is_active' => 1,
        'max_times' => 0
    ];

    private $skuList;

    public static function boot()
    {
        parent::boot();

        static::saving(function(self $promocode) {
            $promocode->generate();

            if ($promocode->isPersonal() && is_null($promocode->max_times)) {
                $promocode->max_times = 0;
            }

            if (is_null($promocode->used_times)) {
                $promocode->used_times = 0;
            }
        });
    }

    public static function kinds()
    {
        return [
            self::KIND_PERSONAL => ucfirst(self::KIND_PERSONAL),
            self::KIND_GROUP => ucfirst(self::KIND_GROUP),
        ];
    }

    /**
     * @param string $code
     * @return static|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function findByCode($code)
    {
        return static::query()->where('code', $code)->first();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function generate()
    {
        if (!$this->code) {
            $this->code = Str::random(15);
        }
    }

    public function isRelevant(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->max_times > 0 && $this->used_times >= $this->max_times) {
            return false;
        }

        return true;
    }

    public function isActive(): bool
    {
        if ($this->is_active == 0) {
            return false;
        }

        if ($this->validity_date && $this->validity_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function isPersonal(): bool
    {
        return $this->kind == self::KIND_PERSONAL;
    }

    public function isGroup()
    {
        return $this->kind == self::KIND_GROUP;
    }

    public function generateConfirmCode()
    {
        $this->confirm_code = Str::lower(Str::random(6));
    }

    public function incrementTimes()
    {
        $this->used_times++;
    }

    public function confirmation($confirmCode): bool
    {
        return $this->confirm_code == $confirmCode;
    }

    public function getSkuListAttribute()
    {
        return explode(',', $this->products_sku);
    }

    /**
     * @param float $price
     * @param int $totalItems
     * @return array
     */
    public function calcParams($price, $totalItems)
    {
        $priceOld = $price;

        if ($this->discount > 0) {
            $discountedPrice = Math::ceilUp(Math::minusPercent($price, $this->discount));
            $discountValue = $this->discount_value;
        } else {
            $discountValue =  CurrencyRate::convertByUserCurrency($this->discount_value);
            $discountedPrice = $price - floor($discountValue / $totalItems);

            if ($discountedPrice <= 0) {
                $discountedPrice = $price;
                $priceOld = 0;
            }
        }

        return [
            'code' => $this->code,
            'kind' => $this->kind,
            'discount' => $this->discount,
            'discount_value' => $discountValue,
            'price_old' => $priceOld,
            'price' => $discountedPrice,
            'discount_amount' => round($price - $discountedPrice, 2),
        ];
    }

    public function containsSku($sku)
    {
        return in_array($sku, $this->sku_list);
    }

    /**
     * @param IPromocode|Model $item
     * @return bool
     */
    public function hasDiscount(IPromocode $item)
    {
        if ($this->products_sku && !$this->containsSku($item->getSku())) {
            return false;
        }

        if ($this->category && !in_array($item->getCategorySlug(), $this->category)) {
            return false;
        }

        if ($this->collections) {
            if (!in_array('ring_collection_id', $item->getAttributes()) || !in_array($item->getCollectionId(), $this->collections)) {
                return false;
            }
        }

        return true;
    }

    public function fillDiscount(IPromocode $cartItem, int $totalItems)
    {
        $params = $this->calcParams($cartItem->calculated_price, $totalItems);
        $cartItem->promocode = $params;
        $cartItem->old_calculated_price = $params['price_old'];
        $cartItem->calculated_price = $params['price'];
    }
}
