<?php

namespace lenal\catalog\Models\Products;

use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

/**
 * @property integer $id
 * @property integer $product_field_id
 * @property string $product_type
 * @property integer $product_id
 * @property string $value
 *
 * @property WeddingRing|Product|EngagementRing $product
 * @property ProductField $field
 */
class ProductFieldAssign extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_field_id',
        'value',
    ];

    public function product()
    {
        return $this->morphTo();
    }

    public function field()
    {
        return $this->hasOne(ProductField::class, 'id', 'product_field_id');
    }
}
