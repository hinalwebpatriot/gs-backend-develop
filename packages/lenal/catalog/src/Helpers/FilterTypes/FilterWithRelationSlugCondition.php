<?php

namespace lenal\catalog\Helpers\FilterTypes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class FilterWithRelationSlugCondition
 *
 * @package lenal\catalog\Helpers\FilterTypes
 */
class FilterWithRelationSlugCondition extends FilterType
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
     * @var Collection
     */
    private $filter_values;
    /**
     * @var string
     */
    private $filter_condition;

    /**
     * FilterWithRelationSlug constructor.
     *
     * @param Builder           $model_builder
     * @param string            $relation_name
     * @param null|array        $filter_values
     * @param null|string       $filter_condition
     */
    public function __construct(
        Builder $model_builder,
        string $relation_name,
        ?array $filter_values,
        ?string $filter_condition = '='
    )
    {
        $this->model_builder = $model_builder;
        $this->relation_name = $relation_name;
        $this->filter_values = collect($filter_values);
        $this->filter_condition = $filter_condition;
    }

    public function filter()
    {
        if ($this->filter_values->count() == 0) {
            return false;
        }

        $this->model_builder->whereHas($this->relation_name, function (Builder $query) {
            $query->where(function ($query) {
                $this->filter_values
                    ->each(function($filter_value) use ($query) {
                        $query->orWhere('slug', $this->filter_condition, $filter_value);
                    });
            });
        });

        return true;
    }
}