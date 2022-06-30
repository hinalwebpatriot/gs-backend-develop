<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterWithProperty
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterWithProperty extends FilterType
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
     * @var null|string|array
     */
    private $filter_value;
    /**
     * @var string|null
     */
    private $filter_condition;

    /**
     * FilterWithProperty constructor.
     *
     * @param Builder           $model_builder
     * @param string            $filter_key
     * @param null|string|array $filter_value
     * @param null|string       $filter_condition
     */
    public function __construct(
        Builder $model_builder,
        string $filter_key,
        $filter_value,
        ?string $filter_condition = '='
    )
    {
        $this->model_builder = $model_builder;
        $this->filter_key = $filter_key;
        $this->filter_value = $filter_value;
        $this->filter_condition = $filter_condition;
    }

    public function filter()
    {
        if (is_null($this->filter_value)) {
            return false;
        }

        is_array($this->filter_value)
            ? $this->model_builder
            ->whereIn($this->filter_key, $this->filter_value)
            : $this->model_builder
            ->where($this->filter_key, $this->filter_condition, $this->filter_value);

        return true;
    }
}