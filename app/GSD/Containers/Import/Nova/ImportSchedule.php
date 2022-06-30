<?php


namespace GSD\Containers\Import\Nova;


use App\Nova\Manufacturer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

/**
 * Class ImportSchedule
 * @package GSD\Containers\Import\Nova
 */
class ImportSchedule extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Import\Models\ImportSchedule::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf('Import: %s', $this->manufacturer->title);
    }

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
        return 'Schedules';
    }

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            BelongsTo::make('Manufacturer', 'manufacturer', Manufacturer::class)->required(),
            Text::make('Schedule', 'schedule')
                ->help('Cron style write. Leave blank to run immediately.'),
            Select::make('Time zone', 'time_zone')->options([
                'Europe/Kiev'      => 'Europe/Kiev',
                'Australia/Sydney' => 'Australia/Sydney',
            ]),
            Boolean::make('Active', 'is_active')
        ];
    }
}