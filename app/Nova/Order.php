<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;


class Order extends Resource
{
    use TabsOnEdit;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'lenal\catalog\Models\Order';

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
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return trans('nova.orders.sidebar_title');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Tabs::make('Order', [
                Tab::make(trans('nova.orders.tabs.general'), [
                    ID::make()->sortable(),
                    Text::make('Email', 'email')->rules('required'),
                    Text::make('First Name', 'first_name')->rules('required'),
                    Text::make('Last Name', 'last_name'),
                    Text::make('Phone Number', 'phone_number'),
                    Text::make('Add. phone number', 'additional_phone_number')->hideFromIndex(),
                    Text::make('Address', 'address')->hideFromIndex(),
                    Text::make('Company Name', 'company_name')->hideFromIndex(),
                    Text::make('Town/City', 'town_city')->hideFromIndex(),
                    Text::make('Zip Postal Coder', 'zip_postal_code')->hideFromIndex(),
                    Text::make('Country', 'country')->hideFromIndex(),
                    Text::make('State', 'state')->hideFromIndex(),
                    Boolean::make("Billing Address isn't specified", 'billing_address')->hideFromIndex(),
                    Boolean::make('Special Package', 'special_package')->hideFromIndex(),
                    Boolean::make('Gift', 'gift')->hideFromIndex(),
                    Text::make('Comment', 'comment')->hideFromIndex(),
                    Text::make('Showroom', 'id_showroom')->hideFromIndex(),
                    Text::make('Order kind', 'kind')->withMeta([
                        'extraAttributes' => [
                            'readonly' => true,
                        ]
                    ]),
                    Date::make('Update date', 'updated_at')->hideFromIndex(),
                    Date::make('Create date', 'created_at'),
                ]),
                Tab::make(trans('nova.orders.tabs.delivery'), [
                    Text::make('First name', 'first_name_home')->hideFromIndex(),
                    Text::make('Last name', 'last_name_home')->hideFromIndex(),
                    Text::make('Phone number', 'phone_number_home')->hideFromIndex(),
                    Text::make('Add. phone number', 'add_phone_number_home')->hideFromIndex(),
                    Text::make('Address', 'address_home')->hideFromIndex(),
                    Text::make('Town/City', 'town_city_home')->hideFromIndex(),
                    Text::make('Zip Postal Coder', 'zip_postal_code_home')->hideFromIndex(),
                    Text::make('Country', 'country_home')->hideFromIndex(),
                    Text::make('State', 'state_home')->hideFromIndex(),
                    Text::make('Appartman Number', 'appartman_number_home')->hideFromIndex(),
                ]),
                Tab::make(trans('nova.orders.tabs.items'), [
                    Text::make('Total_price', 'total_price'),
                    Text::make('Currency', 'currency'),
                    HasMany::make('CartItem', "cartItems"),
                ]),
                Tab::make('Payment', [
                    BelongsTo::make('Payment method', 'paySystem', Paysystem::class),
                    BelongsTo::make('Order status', "statusOrder", Status::class),
                    DateTime::make('Delivered', 'delivered_at')->nullable()->hideFromIndex(),
                    Files::make('Invoice', 'invoices')->hideFromIndex()
                ]),
                Tab::make('Service invoice', [
                    HasOne::make('Invoice', 'invoice', Invoice::class),
                ]),
            ])->withToolbar()
        ];
    }

    /**
     * Get the displayble group of the resource.
     *
     * @return string
     */
    public static $category = 'Order info';

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [
        ];
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

        ];
    }
}
