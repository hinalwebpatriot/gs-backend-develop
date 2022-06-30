<?php

namespace lenal\search\Traits;

/**
 * Trait EngagementSearchable
 *
 * @package lenal\search
 */
trait EngagementSearchable
{
    public $isRT = true;
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'diamonds_engagements';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $titles = $this->h1." ".$this->h2;

        return [
            'title' => $titles,
            'sku'   => $this->sku,
            'metal' => $this->metal->title,
        ];
    }
}