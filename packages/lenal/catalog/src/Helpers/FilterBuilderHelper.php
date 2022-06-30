<?php

namespace lenal\catalog\Helpers;

use lenal\catalog\Helpers\FilterTypes\FilterType;
use lenal\catalog\Helpers\SortTypes\SortType;

/**
 * Class FilterBuilderHelper
 *
 * @package lenal\catalog\Contracts
 */
class FilterBuilderHelper
{
    private $appliedFilter = false;
    /**
     * @param array|FilterType $filter_type
     *
     * @return void
     */
    public function applyFilter($filter_type): void
    {
        if (is_array($filter_type)) {
            foreach ($filter_type as $filter) {
                $this->applyFilter($filter);
            }

            return;
        }

        if ($filter_type instanceof FilterType) {
            if ($filter_type->filter()) {
                $this->appliedFilter = true;
            }
        }
    }

    /**
     * @param array|SortType $sort_type
     *
     * @return void
     */
    public function applyOrder($sort_type): void
    {
        if (is_array($sort_type)) {
            foreach ($sort_type as $order) {
                $this->applyOrder($order);
            }

            return;
        }

        if ($sort_type instanceof SortType) {
            $applied = $sort_type->sort();

            if (!$this->appliedFilter && $applied) {
                $this->appliedFilter = $applied;
            }
        }
    }

    public function hasAppliedFilter()
    {
        return $this->appliedFilter;
    }
}