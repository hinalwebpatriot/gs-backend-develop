<?php


namespace GSD\Containers\Referral\Nova;


use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

/**
 * Ресурс для новы.
 * Покупатели из црм товер
 *
 * Class TowerCustomer
 * @package App\GSD\Containers\Referral\Nova
 */
class TowerCustomer extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Referral\Models\TowerCustomer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf('%s %s - %s', $this->first_name, $this->last_name, $this->email);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'email', 'phone', 'first_name', 'last_name',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Tower Customers';
    }

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Email', 'email')->rules(['required']),
            Text::make('Phone', 'phone')->nullable(),
            Text::make('First Name', 'first_name')->nullable(),
            Text::make('Last Name', 'last_name')->nullable(),
            DateTime::make('Last Sent', 'last_sent_at')->nullable()
        ];
    }
}