<?php

namespace App\Nova;

use App\Nova\Actions\ApproveReview;
use App\Nova\Filters\ReviewIsApproved;
use App\Nova\Filters\ReviewType;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Review extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\reviews\Models\Review';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'author_email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'author_name', 'author_email', 'title', 'text'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Boolean::make('Approved', 'is_active'),
            Text::make('Title'),
            Text::make('Author name'),
            Text::make('Author email'),
            Textarea::make('Text'),
            Select::make('Rate')->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
            Images::make('Photos', 'photos')
                ->hideFromIndex(),
            MorphTo::make('Product')
                ->types([
                    Diamond::class,
                    EngagementRing::class,
                    WeddingRing::class,
                    Product::class
                ])
                ->searchable()
                ->nullable()
                ->help('Search by SKU'),
            DateTime::make('Added', 'created_at')->sortable(),
            DateTime::make('Updated', 'updated_at')->sortable()->onlyOnDetail()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ReviewIsApproved(),
            new ReviewType()
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new ApproveReview()
        ];
    }
}
