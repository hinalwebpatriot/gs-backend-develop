<?php

namespace lenal\catalog\Helpers\SortTypes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class SortWithProperty
 *
 * @package lenal\catalog\Helpers\SortTypes
 */
class SortWithProperty extends SortType
{
    /**
     * @var Builder
     */
    private $model_builder;
    /**
     * @var bool
     */
    private $has_order_request_key;
    /**
     * @var string
     */
    private $order_key;
    /**
     * @var null|string
     */
    private $order_type;

    /**
     * SortWithProperty constructor.
     *
     * @param Builder     $model_builder
     * @param bool        $has_order_request_key
     * @param string      $order_key
     * @param null|string $order_type
     */
    public function __construct(
        Builder $model_builder,
        bool $has_order_request_key,
        string $order_key,
        ?string $order_type = 'asc'
    )
    {
        $this->model_builder = $model_builder;
        $this->has_order_request_key = $has_order_request_key;
        $this->order_key = $order_key;
        $this->order_type = $order_type;
    }

    public function sort()
    {
        if (!$this->has_order_request_key) {
            return false;
        }

        $this->model_builder
            ->orderBy($this->order_key, $this->order_type ?? 'asc');

        return true;
    }
}