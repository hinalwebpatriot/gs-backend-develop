<?php

namespace Vpsitua\HasManySinglePage;

use Laravel\Nova\Fields\Field;

class HasManySinglePage extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-single-page';

    public $requestName;

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->requestName = $attribute . '_items';
    }

    public function meta()
    {
        return array_merge([
            'requestName' => $this->requestName
        ], $this->meta);
    }
}
