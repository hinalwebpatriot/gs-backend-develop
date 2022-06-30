<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use lenal\catalog\Models\Invoice as InvoiceModel;
use lenal\catalog\Resources\InvoiceServiceAdminResource;
use Vpsitua\HasManySinglePage\HasManySinglePage;

class Invoice extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Invoice';
    public static $category = 'Catalog';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'alias',
        'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $this->additionalValidation();
        }

        return [
            Tabs::make('Service Invoice', [
                Tab::make('General', [
                    ID::make()->sortable(),

                    Text::make('Alias', 'alias')
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ])
                        ->sortable()
                        ->hideWhenCreating(),

                    Text::make('Url', 'url')
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ])
                        ->hideWhenCreating()
                        ->hideFromIndex()
                        ->hideWhenUpdating(),

                    Text::make('E-mail', 'email')->rules(['required', 'email']),

                    Text::make('Raw price ', 'raw_price')
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ])
                        ->hideWhenCreating(),

                    Text::make('Inc price ', 'inc_price')
                        ->withMeta([
                            'extraAttributes' => [
                                'readonly' => true,
                            ]
                        ])
                        ->hideWhenCreating(),

                    Select::make('Status', 'status')
                        ->rules(['required'])
                        ->options(InvoiceModel::statuses())
                        ->hideWhenCreating(),

                    Date::make('Update date', 'updated_at')
                        ->hideFromIndex()
                        ->hideWhenCreating()
                        ->hideFromIndex(),

                    Date::make('Create date', 'created_at')
                        ->hideWhenCreating(),
                ]),

                Tab::make('Services', [
                    HasManySinglePage::make('Services', 'services', function (Collection $value) {
                        return InvoiceServiceAdminResource::collection($value)->toArray(request());
                    })->hideFromIndex()
                ]),
            ])->withToolbar()
        ];
    }

    public function additionalValidation()
    {
        $items = request()->get('services_items');
        if ($items) {
            Validator::make(json_decode($items, true), [
                '*.title' => 'required',
                '*.description' => 'required',
                '*.price' => 'required|min:0|numeric',
                '*.gst' => 'required|min:0|numeric',
            ], [], [
                '*.title' => 'Service title',
                '*.description' => 'Service description',
                '*.price' => 'Service price',
                '*.gst' => 'Service gst',
            ])->validate();
        }
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
        return [];
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
        return [];
    }

    public static function label()
    {
        return 'Service invoices';
    }
}
