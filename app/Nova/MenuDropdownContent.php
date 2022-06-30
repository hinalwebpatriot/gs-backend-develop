<?php

namespace App\Nova;

use App\Nova\Filters\Locale;
use App\Nova\Filters\MenuDropdownItems;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class MenuDropdownContent extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\additional_content\Models\MenuDropdownContent';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'menu_item';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            Select::make('Menu item')
                ->options($this->menuItems)
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),
            Select::make('Locale')
                ->options(config('translatable.locales'))
                ->sortable()
                ->rules('required')
                ->displayUsingLabels(),
            Images::make('Content', 'menu_dropdowns')
                ->customPropertiesFields([
                    Text::make('Title'),
                    Text::make('Link')
                ])
                ->help('Hover and click edit button to set title and link')
        ];
    }

    private $menuItems = [
        'diamonds' => 'Diamonds',
        'engagement-rings' => 'Engagement rings',
        'wedding-rings' => 'Wedding rings',
    ];

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
        return [
            new MenuDropdownItems,
            new Locale
        ];
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

    public static $category="Additional content";

    public static function label()
    {
        return 'Menu dropdown';
    }
}
