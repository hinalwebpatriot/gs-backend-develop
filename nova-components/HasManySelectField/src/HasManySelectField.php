<?php

namespace HasManySelectField;

use Laravel\Nova\Fields\Field;

class HasManySelectField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-select-field';
    public $resourceModel;
    public $itemModel;
    public $itemRelated;
    public $resourceFormat;
    public $searchColumn;
    public $searchTitle;

    public $showOnIndex = false;
    public $showOnDetail = false;

    public function setResourceModel($name)
    {
        $this->resourceModel = $name;

        return $this;
    }

    public function setItemModel($name)
    {
        $this->itemModel = $name;

        return $this;
    }

    public function setResourceItemRelated($relatedName)
    {
        $this->itemRelated = $relatedName;

        return $this;
    }

    public function setResourceFormat($resourceFormatName)
    {
        $this->resourceFormat = $resourceFormatName;

        return $this;
    }

    public function setSearchColumn($column, $title = '')
    {
        $this->searchColumn = $column;
        $this->searchTitle = $title;

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge([
            'resourceModel' => $this->resourceModel,
            'itemModel' => $this->itemModel,
            'itemRelated' => $this->itemRelated,
            'resourceFormat' => $this->resourceFormat,
            'searchColumn' => $this->searchColumn,
            'searchTitle' => $this->searchTitle,
        ], parent::jsonSerialize());
    }
}
