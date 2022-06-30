<?php


namespace GSD\Containers\Import\Models;


use Carbon\Carbon;
use GSD\Ship\Parents\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use lenal\catalog\Models\Diamonds\Manufacturer;

/**
 * Class ImportSchedule
 * @package GSD\Containers\Import\Models
 *
 * @property int    $id
 * @property int    $manufacturer_id
 * @property string $schedule
 * @property string $time_zone
 * @property bool   $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ImportSchedule extends Model
{
    protected $table = 'import_schedules';

    protected $fillable = ['manufacturer_id', 'schedule', 'time_zone', 'is_active'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}