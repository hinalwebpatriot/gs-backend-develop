<?php

namespace App\Nova\Fields;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;

/**
 * Image class not valid SVG if mimetype `text/html`
 */
class ImagesCommon extends Images
{
    protected $defaultValidatorRules = ['mimes:png,jpg,jpeg,svg,html'];
}