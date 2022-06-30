<?php

namespace lenal\catalog\Services;


use lenal\catalog\Models\Rings\{EngagementRing, WeddingRing};
use lenal\catalog\Models\Products\{Product, ProductFieldAssign};

class ProductCustomFieldService
{
    /**
     * @var Product|WeddingRing|EngagementRing
     */
    private $model;
    private $collection;

    public function __construct($model)
    {
        $this->model = $model;
        $this->collection = request()->get('custom_fields_collection');
    }

    public function sync()
    {
        if (!$this->collection) {
            return ;
        }

        foreach ($this->collection as $fieldId => $value) {
            $assign = $this->model->customFields->firstWhere('product_field_id', $fieldId);
            if (!$assign) {
                $assign = new ProductFieldAssign();
                $assign->product_field_id = $fieldId;
            }

            $assign->value = $value;
            $this->model->customFields()->save($assign);
        }
    }
}