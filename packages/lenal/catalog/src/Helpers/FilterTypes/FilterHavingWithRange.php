<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterHavingWithRange
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterHavingWithRange extends FilterType
{
    /**
     * @var Builder
     */
    private $model_builder;
    /**
     * @var string
     */
    private $filter_key;
    /**
     * @var array|null
     */
    private $request_range;

    /**
     * FilterHavingWithRange constructor.
     *
     * @param Builder    $model_builder
     * @param string     $filter_key
     * @param array|null $request_range
     */
    public function __construct(Builder $model_builder, string $filter_key, ?array $request_range)
    {
        $this->model_builder = $model_builder;
        $this->filter_key = $filter_key;
        $this->request_range = $request_range;
    }

    public function filter()
    {
        if (is_array($this->request_range) && isset($this->request_range['min'], $this->request_range['max'])) {
            $this->model_builder->having($this->filter_key, '>=', $this->request_range['min']);
            $this->model_builder->having($this->filter_key, '<=', $this->request_range['max']);
            return true;
        }

        return false;
    }
}