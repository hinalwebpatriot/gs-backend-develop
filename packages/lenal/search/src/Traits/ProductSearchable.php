<?php

namespace lenal\search\Traits;


/**
 * Trait EngagementSearchable
 *
 * @package lenal\search
 * @mixin \lenal\catalog\Models\Products\Product
 */
trait ProductSearchable
{
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return env('ALGOLIA_INDEX_PREFIX', '') . 'products';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->h1 . " " . $this->h2,
            'sku'   => $this->sku,
            'metal' => $this->metal->title,
        ];
    }
}