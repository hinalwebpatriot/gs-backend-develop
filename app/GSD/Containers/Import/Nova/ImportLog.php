<?php


namespace GSD\Containers\Import\Nova;


use App\Nova\Manufacturer;
use GSD\Containers\Import\Enums\ImportLogTypeEnum;
use GSD\Containers\Import\Nova\Filters\Manufacturers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

/**
 * Class ImportLog
 * @package GSD\Containers\Import\Nova
 */
class ImportLog extends \App\Nova\Resource
{
    public static function label()
    {
        return 'Errors';
    }
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Import\Models\ImportLog::class;

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
            BelongsTo::make('Manufacturer', 'manufacturer', Manufacturer::class)->required(),
            Select::make('Type', 'type')->options(ImportLogTypeEnum::toArray()),
            Text::make('Text', 'text'),
            Textarea::make('Data', 'data')->onlyOnDetail()
        ];
    }

    public function filters(Request $request)
    {
        return [
            new Manufacturers(),
        ];
    }
}