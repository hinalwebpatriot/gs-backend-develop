<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;
use lenal\offers\Models\Offer;

class AttachOffer extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        set_time_limit(0);
        ini_set("memory_limit", "-1");

        $models->each(function (Model $model) use ($fields) {
            $field_is_attaached = $model->offers
                ->where('id', $fields->offer)
                ->count() > 0;

            if (!$field_is_attaached) {
                $model->offers()->attach($fields->offer);
            }
        });
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $offers = Offer::all()
            ->pluck('title', 'id')
            ->toArray();

        return [
            Select::make('Offers', 'offer')
                ->options($offers)
                ->displayUsingLabels(),
        ];
    }
}
