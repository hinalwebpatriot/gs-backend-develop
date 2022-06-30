<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\Metal;

/**
 * @property integer $id
 * @property string $category_slug
 * @property integer $metal_id
 * @property integer $with_diamond
 * @property integer $delivery_period
 * @property integer $delivery_period_wk
 * @property string $product_sku
 * @property integer $ring_style_id
 *
 * @property Metal $metal
 * @property EngagementRingStyle $engagementRingStyle
 */
class DeliverySchema extends Model
{
    public $timestamps = false;
    protected $table = 'delivery_schema';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        parent::saving(function(self $model) {
            if ($model->category_slug != Category::ENGAGEMENT) {
                $model->with_diamond = 0;
            }
        });
    }

    /**
     * @param string $category
     * @param int $metalId
     * @param bool $diamond
     * @param string $sku
     * @param integer $styleId
     * @return static|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function findDeliveryPeriod($category, $metalId, $diamond = false, $sku = null, $styleId = null)
    {
        $query = static::query();

        if ($category) {
            $query->where('category_slug', $category);
        }

        if ($metalId) {
            $query->where('metal_id', $metalId);
        }

        if ($sku) {
            $query->where(function (Builder $builder) use ($sku) {
                $builder->whereNull('product_sku');
                $builder->orWhereRaw("replace(concat(',', product_sku, ','), ' ', '') like '%,$sku,%'");
            });
        }

        if ($styleId) {
            $query->where(function (Builder $builder) use ($styleId) {
                $builder->whereNull('ring_style_id');
                $builder->orWhere('ring_style_id', $styleId);
            });
            $query->orderByDesc('ring_style_id');
        }

        $query->where('with_diamond', $diamond ? 1 : 0);
        $query->orderByDesc('product_sku');

        return $query->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function metal()
    {
        return $this->belongsTo(Metal::class)->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function engagementRingStyle()
    {
        return $this->belongsTo(EngagementRingStyle::class, 'ring_style_id', 'id');
    }

    public function deliveryPeriodParams()
    {
        if ($this->delivery_period > 0) {
            return ['period' => $this->delivery_period, 'unit' => 'days'];
        } else {
            return ['period' => $this->delivery_period_wk, 'unit' => 'weeks'];
        }
    }

    public function maxDays()
    {
        return $this->delivery_period ?: $this->delivery_period_wk * 7;
    }
}


