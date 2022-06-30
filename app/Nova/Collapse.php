<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Collapse extends Resource
{
    public static $displayInNavigation = false;

    public static $perPageViaRelationship = false;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\seo\Models\Collapse';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title'
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
            ID::make('ID', 'id')->sortable(),
            MorphTo::make('Parent', 'collapsable')->types([
                SEOBlock::class,
            ])->onlyOnDetail(),
            Text::make('Title', 'title')->required()->sortable(),
            Textarea::make('Content', 'content')->required()->hideFromIndex(),
        ];
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return "Collapses";
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return "Collapse";
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->when(empty($request->get('orderBy')), function ($q) {
            $q->getQuery()->orders = [];
            return $q->orderBy(static::$model::orderColumnName());
        });

        return $query;
    }

    /**
     * Prepare the resource for JSON serialization.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Support\Collection $fields
     *
     * @return array
     */
    public function serializeForIndex(NovaRequest $request, $fields = null)
    {
        return array_merge(parent::serializeForIndex($request, $fields), [
            'sortable' => true
        ]);
    }
}
