<?php

namespace App\Nova\Observers;


use lenal\catalog\Enums\DiamondTypeEnum;
use lenal\catalog\Models\Diamonds\Diamond;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class DiamondObserver
{
    public function saving(Diamond $model)
    {
        Nova::whenServing(function (NovaRequest $request) use ($model) {
            if ($request->resource() == \App\Nova\Diamond::class) {
                $model->type = DiamondTypeEnum::NATURAL();
            }
            if ($request->resource() == \App\Nova\DiamondLab::class) {
                $model->type = DiamondTypeEnum::LAB();
            }
        });
    }
}
