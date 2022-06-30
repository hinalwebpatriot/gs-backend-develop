<?php


namespace GSD\Containers\Import\Nova;


use App\Nova\Manufacturer;
use GSD\Containers\Import\Enums\ImportLogTypeEnum;
use GSD\Containers\Import\Nova\Filters\Manufacturers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

/**
 * Class ImportStatistic
 * @package GSD\Containers\Import\Nova
 */
class ImportStatistic extends \App\Nova\Resource
{
    public static $title = 'Statistic';
    public static function label()
    {
        return 'Statistics';
    }

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Import\Models\ImportStatistic::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'import_id',
    ];

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make('id'),
            BelongsTo::make('Manufacturer', 'manufacturer', Manufacturer::class)->required(),
            Number::make('Count', 'records_count'),
            Number::make('Ignored', 'records_ignore'),
            Number::make('Created', 'records_create'),
            Number::make('Updated', 'records_update'),
            Number::make('Deleted', 'records_delete'),
            Number::make('Errored', 'records_error'),
            DateTime::make('Imported', 'created_at')->sortable(),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new Manufacturers(),
        ];
    }
}