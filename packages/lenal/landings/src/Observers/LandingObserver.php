<?php

namespace lenal\landings\Observers;


use Illuminate\Support\Str;
use lenal\landings\Models\Landing;

/**
 * Class DiamondObserver
 *
 * @package lenal\catalog\Observers
 */
class LandingObserver
{
    /**
     * @param Landing $model
     */
    public function saving(Landing $model)
    {
        $model->slug = Str::slug($model->slug);
    }

    public function deleting(Landing $model)
    {
        $model->clearMediaCollection($model::MEDIA_COLLECTION);
        $model->rings()->detach();
        $model->diamonds()->detach();
    }
}
