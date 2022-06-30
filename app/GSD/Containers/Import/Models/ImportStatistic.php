<?php


namespace GSD\Containers\Import\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use lenal\catalog\Models\Diamonds\Manufacturer;

/**
 * Class ImportStatistic
 * @package GSD\Containers\Import\Models
 *
 * @property int    $id
 * @property int    $import_id
 * @property int    $manufacturer_id
 * @property int    $records_count
 * @property int    $records_invalid
 * @property int    $records_ignore
 * @property int    $records_create
 * @property int    $records_update
 * @property int    $records_delete
 * @property int    $records_error
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ImportStatistic extends Model
{
    protected $table = 'import_statistics';

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}