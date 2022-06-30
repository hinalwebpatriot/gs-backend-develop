<?php

namespace lenal\catalog\Helpers;

use Illuminate\Database\Eloquent\Model;

trait ModelRelationValue
{
    /**
     * @param string $model_class
     * @param null|string $slug
     *
     * @param bool $titleAsArray
     * @return null
     */
    private function getRelationValue(string $model_class, ?string $slug, $titleAsArray = true)
    {
        if (!class_exists($model_class) || is_null($slug)) {
            return null;
        }

        $model = $model_class::where('slug', $slug)->first();
        if (!$model) {
            $model = $model_class::create([
                'slug' => $slug,
                'title' => ''
            ]);
        }

        return $model instanceof Model
            ? $model->id
            : null;
    }
}