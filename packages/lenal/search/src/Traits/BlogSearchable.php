<?php

namespace lenal\search\Traits;

/**
 * Trait BlogSearchable
 *
 * @package lenal\search
 */
trait BlogSearchable
{
    public $isRT = true;
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'blog';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title'        => $this->title,
            'preview_text' => $this->preview_text,
            'view_count'   => $this->view_count,
        ];
    }
}