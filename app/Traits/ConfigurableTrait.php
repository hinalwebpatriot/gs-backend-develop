<?php

namespace App\Traits;


trait ConfigurableTrait
{
    public function initProperties($params)
    {
        foreach ($params as $param => $value) {
            if (property_exists($this, $param)) {
                $this->$param = $value;
            }
        }
    }
}