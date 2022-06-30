<?php

namespace lenal\catalog\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use lenal\catalog\Models\Rings\RingSize;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $product_type
 * @property integer $product_id
 * @property integer $quantity
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $size_slug
 * @property integer $order_id
 * @property number $price
 * @property string $engraving
 * @property string $engraving_font
 * @property mixed $price_old
 *
 * @property RingSize $size
 *
 * @property \lenal\catalog\Models\Diamonds\Diamond|\lenal\catalog\Models\Rings\EngagementRing|\lenal\catalog\Models\Rings\WeddingRing $product
 */
class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'product_type', 'product_id', 'quantity', 'created_at', 'updated_at', 'size_slug',
        'order_id', 'price', 'engraving', 'engraving_font', 'price_old',
    ];

    public function product()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, "id");
    }

    public function size()
    {
        return $this->hasOne(RingSize::class, 'slug', 'size_slug');
    }

    public function discount()
    {
        $discount =  $this->price_old > 0 ? $this->price_old - $this->price : 0;

        return $discount > 0 ? $discount : 0;
    }
}
