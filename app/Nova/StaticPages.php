<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use MrMonat\Translatable\Translatable;
use Waynestate\Nova\CKEditor;

class StaticPages extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\static_pages\Models\StaticPage';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title_translatable';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title_translatable', 'text_translatable'
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
            ID::make()->sortable(),
            Translatable::make('Title', 'title_translatable')->singleLine()->rules('required'),
            Text::make('Slug', 'code')->rules('required'),
            Image::make('Image', 'image')->disk(config('filesystems.cloud')),

            CKEditor::make('Text', 'text_translatable')->options([
                'filebrowserBrowseUrl' => '/elfinder/ckeditor'
            ])->hideFromIndex(),

            //Trix::make('Biography')->withFiles('public'),
            //Markdown::make("Text", 'text_translatable')->hideFromIndex(),

            /*Translatable::make('Text', 'text_translatable')
                ->trix()
                ->rules('required', 'max:1000')
                ->hideFromIndex(),*/
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
    public static function label() {
        return 'Static Pages';
    }
    public static function singularLabel()
    {
        return 'static page';
    }

    public static $category = 'Static content';
}
