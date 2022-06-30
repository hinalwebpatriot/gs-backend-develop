<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterWithRelationRange
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterWithRelationRange extends FilterType
{
    /**
     * @var Builder
     */
    private $model_builder;
    /**
     * @var string
     */
    private $relation_name;
    /**
     * @var string
     */
    private $filter_key;
    /**
     * @var array|null
     */
    private $request_range;

    /**
     * FilterWithRelationRange constructor.
     *
     * @param Builder    $model_builder
     * @param string     $relation_name
     * @param string     $filter_key
     * @param array|null $request_range
     */
    public function __construct(
        Builder $model_builder,
        string $relation_name,
        string $filter_key,
        ?array $request_range
    )
    {
        $this->model_builder = $model_builder;
        $this->relation_name = $relation_name;
        $this->filter_key = $filter_key;
        $this->request_range = $request_range;
    }

    public function filter()
    {
        $isApplied = false;

        $this->model_builder->whereHas($this->relation_name, function(Builder $query) use (&$isApplied) {
            $isApplied = FilterWithRange::make($query, $this->filter_key, $this->request_range)->filter();
        });

        return $isApplied;
    }
}