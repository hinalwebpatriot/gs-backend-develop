<?php

namespace lenal\search\Traits;

/**
 * Trait DiamondSearchable
 *
 * @package lenal\search
 */
trait DiamondSearchable
{
    public $isRT = true;
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'diamonds';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $locales = collect(config('translatable.locales'))->keys();

        $titles = $locales->map(function ($locale) {
            app()->setLocale($locale);

            return $this->title;
        });

        return [
            'title' => $titles,
            'sku'   => $this->sku,
        ];
    }
}