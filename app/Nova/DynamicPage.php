<?php

namespace App\Nova;

use DynamicPageSeeder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DynamicPage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\blocks\Models\DynamicPage';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'page';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'page',
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
            Text::make('Page'),
            HasMany::make('Certificate block', 'certificateBlocks', 'App\Nova\StaticBlockCertificate')
                ->canSee(function () { return $this->isFeedPage(); }),

            HasOne::make('Guide block', 'guideBlock', 'App\Nova\StaticBlockGuide')
                ->canSee(function () { return $this->isCatalogPage(); }),

            HasOne::make('Why GS Diamonds', 'descriptionBlock', 'App\Nova\StaticBlockDescription')
                ->canSee(function () { return $this->isPage('diamonds-detail'); }),

            HasMany::make('Mind blowing promo', 'promoBlocks', 'App\Nova\StaticBlockPromo')
                ->canSee(function () { return $this->isFeedPage(); }),

            HasMany::make('Slides', 'slider',
                'App\Nova\StaticBlockSlider')
                ->canSee(function () { return $this->isFeedPage(); }),

            HasMany::make('Tags', 'tagLinks',
                'App\Nova\StaticBlockTagLink')
                ->canSee(function () { return $this->isPage('contacts'); }),

            HasOne::make('Schedule', 'textBlock',
                'App\Nova\StaticBlockText')
                ->canSee(function () { return $this->isPage('contacts'); }),

            HasOne::make('Slider Worth paying attention', 'recommendProducts',
                'App\Nova\StaticBlockRecommend')
                ->canSee(function () { return ($this->isPage('homepage') || $this->isPage('detail')); }),

            HasOne::make('Complete the look', 'completeLookBlock', 'App\Nova\StaticBlockCompleteLook')
                ->canSee(function () { return $this->isPage('diamonds-detail'); }),

            HasOne::make('Slider Let Your occasion be special', 'occasionSpecial',
                'App\Nova\StaticBlockOccasionSpecial')
                ->canSee(function () { return ($this->isPage('homepage')); }),

            HasOne::make('Second rings slider', 'secondRingsSlider',
                'App\Nova\StaticBlockSecondRingsSlider')
                ->canSee(function () { return ($this->isPage('diamonds-feed')); }),

            HasOne::make('Top picks of the month', 'topPicks',
                'App\Nova\StaticBlockTopPicks')
                ->canSee(function () { return $this->isRingsFeedPage() || $this->isPage('products-feed'); }),

            HasOne::make('Shopping rings made easy', 'additionalInfoBlock',
                'App\Nova\StaticBlockAdditionalInfo')
                ->canSee(function () { return $this->isRingsFeedPage(); }),

            HasOne::make('Before you surprise your loved one', 'additionalInfoIcons',
                'App\Nova\StaticBlockAdditionalIcons')
                ->canSee(function () { return $this->isRingsFeedPage(); }),

            HasOne::make('Story about custom jewelry', 'storyCustomJewelry',
                'App\Nova\StaticBlockStoryCustomJewelry')
                ->canSee(function () { return $this->isPage('homepage'); }),
        ];
    }

    private function isPage($page)
    {
        return str_contains($this->page, $page);
    }

    private function isFeedPage()
    {
        return str_contains($this->page, 'feed');
    }

    private function isRingsFeedPage()
    {
        return ($this->isPage('engagement-rings-feed') || $this->isPage('wedding-rings-feed'));
    }

    private function isCatalogPage()
    {
        return str_contains($this->page, 'feed') || str_contains($this->page, 'detail');
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
        return 'Dynamic pages';
    }

    public static function singularLabel()
    {
        return 'Dynamic page';
    }

    public static $category = 'Static content';

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public static $indexDefaultOrder = ['id' => 'asc'];

    public static function indexQuery(NovaRequest $request, $query)
    {
        $seeder = new DynamicPageSeeder();
        $seeder->run();

        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
    }

}
