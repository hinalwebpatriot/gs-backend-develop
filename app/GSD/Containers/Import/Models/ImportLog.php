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
 * @property int    $import_id
 * @property int    $manufacturer_id
 * @property string $type
 * @property string $text
 * @property string $data
 * @property Carbon $created_at
 */
class ImportLog extends Model
{
    protected $table = 'import_logs';
    protected $primaryKey = 'import_id';

    protected $fillable = ['import_id', 'manufacturer_id', 'type', 'text', 'data'];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}