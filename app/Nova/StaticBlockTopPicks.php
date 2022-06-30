<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use lenal\blocks\Models\DynamicPage as DynamicPageModel;
use MrMonat\Translatable\Translatable;
use Outhebox\NovaHiddenField\HiddenField;

class StaticBlockTopPicks extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\blocks\Models\StaticBlock';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'text';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

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
            BelongsTo::make('Page', 'dynamic_page_block', 'App\Nova\DynamicPage')->exceptOnForms(),
            HiddenField::make('Block type')
                ->defaultValue('top-picks')->hideFromIndex()->hideFromDetail(),

            Translatable::make('Title')->rules('required')->singleLine(),

            BelongsToMany::make('Engagement Rings', 'blockEngagementRings')
                ->searchable()
                ->canSee(function () { return $this->isDynamicPage($this->dynamic_page_id,'engagement-rings-feed'); }),
            BelongsToMany::make('Wedding Rings', 'blockWeddingRings')->searchable()
                ->canSee(function () { return $this->isDynamicPage($this->dynamic_page_id,'wedding-rings-feed'); })
        ];
    }

    private function isDynamicPage($pageId, $code)
    {
        return boolval($page = DynamicPageModel::where(['id' => $pageId, 'page' => $code]) ->first());
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

    public static function label()
    {
        return 'Top picks';
    }

    public static function singularLabel()
    {
        return 'Top picks';
    }

    public static $displayInNavigation = false;

    public static $category = 'Static content';

}
