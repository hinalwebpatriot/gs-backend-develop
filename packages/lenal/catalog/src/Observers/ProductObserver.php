<?php

namespace lenal\catalog\Observers;

use Illuminate\Support\Str;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Services\ProductCustomFieldService;

/**
 * Class ProductObserver
 *
 * @package lenal\catalog\Observers
 */
class ProductObserver
{
    /**
     * @param Product $product
     */
    public function saving(Product $product)
    {
        if (!$product->group_sku) {
            $product->group_sku = $product->item_name;
        }

        $product->group_sku = Str::kebab($product->group_sku);

        if (is_null($product->delivery_period)) {
            $product->delivery_period = 0;
        }
    }

    /**
     * @param Product $product
     */
    public function deleting(Product $product)
    {
        $product->unsearchable();
    }

    public function saved(Product $product)
    {
        $product->flushImageCache();

        (new ProductCustomFieldService($product))->sync();
    }
}
