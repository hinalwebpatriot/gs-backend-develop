<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;

/**
 * Class FilterWithRelationSlug
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterWithRelationSlug extends FilterType
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
     * @var string|array|null
     */
    private $filter_value;
    /**
     * @var string
     */
    private $filter_condition;

    /**
     * FilterWithRelationSlug constructor.
     *
     * @param Builder           $model_builder
     * @param string            $relation_name
     * @param null|string|array $filter_value
     * @param null|string       $filter_condition
     */
    public function __construct(
        Builder $model_builder,
        string $relation_name,
        $filter_value,
        ?string $filter_condition = '='
    )
    {
        $this->model_builder = $model_builder;
        $this->relation_name = $relation_name;
        $this->filter_value = $filter_value;
        $this->filter_condition = $filter_condition;
    }

    public function filter()
    {
        if (is_null($this->filter_value)) {
            return false;
        }

        $this->model_builder->whereHas($this->relation_name, function (Builder $query) {
            is_array($this->filter_value)
                ? $query->whereIn('slug', $this->filter_value)
                : $query->where('slug', $this->filter_condition, $this->filter_value);
        });

        return true;
    }
}