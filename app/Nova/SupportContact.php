<?php

namespace App\Nova;

use Fourstacks\NovaRepeatableFields\Repeater;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class SupportContact extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\social\Models\ContactLink';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'service';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'service',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            Text::make('Service')
                ->hideWhenUpdating()
                ->rules('unique:contact_links'),
            Text::make('Service') // readonly field on update form
                ->withMeta(['extraAttributes' => ['disabled' => true]])
                ->canSee(function (){ return $this->service; })
                ->onlyOnForms(),
            Repeater::make('Contact (username / number / etc.)', 'value')
                ->addField(['label' => 'value'])
                ->summaryLabel('Contacts')
                ->addButtonText('Add contact')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static $category = 'Social';

    public static function label()
    {
        return 'Support contacts';
    }
}
