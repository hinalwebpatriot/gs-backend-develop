<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

/**
 * @property integer $id
 * @property integer $list_id
 * @property string $product_type
 * @property integer $product_id
 * @property mixed $product_state
 *
 * @property Diamond|EngagementRing|Product|WeddingRing|ProductStates $product
 */
class SharingProduct extends Model
{
    public $timestamps = false;

    protected $fillable = ['date_added'];
    protected $casts = ['product_state' => 'array'];

    public static function boot()
    {
        parent::boot();

        static::saving(function(self $item) {
            $item->initProductState();
        });
    }

    public function list()
    {
        return $this->belongsTo(ProductSharingList::class);
    }

    public function product()
    {
        return $this->morphTo();
    }

    public function initProductState()
    {
        if ($this->product) {
            $state = $this->product->getAttributes();

            if (isset($state['item_name'])) {
                $state['item_name'] = $this->product->item_name;
            }

            $this->product_state = $state;
        }
    }

    public function restoreProduct()
    {
        if ($this->product_state) {
            $product = new $this->product_type;
            $product->fill($this->product_state);
            return $product;
        }

        return null;
    }
}
