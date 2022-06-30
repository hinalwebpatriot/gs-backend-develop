<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterWithRelationList
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterWithRelationList extends FilterType
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
     * @var array
     */
    private $filter_list;

    /**
     * FilterWithRelationList constructor.
     *
     * @param Builder $model_builder
     * @param string  $relation_name
     * @param array   $filter_list
     */
    public function __construct(Builder $model_builder, string $relation_name, array $filter_list)
    {
        $this->model_builder = $model_builder;
        $this->relation_name = $relation_name;
        $this->filter_list = $filter_list;
    }

    public function filter()
    {
        if (!$this->filter_list) {
            return false;
        }

        $this->model_builder->whereHas($this->relation_name, function (Builder $query) {
            $query->whereIn('slug', $this->filter_list);
        });

        return true;
    }
}