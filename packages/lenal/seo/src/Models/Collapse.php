<?php

namespace lenal\seo\Models;

use Illuminate\Database\Eloquent\Model;
use Ofcold\NovaSortable\SortableTrait;

/**
 * Class Collapse
 * @package lenal\seo\Models
 *
 * @property int $id
 * @property int $collapsable_id
 * @property string $collapsable_type
 * @property string $title
 * @property string $content
 *
 * @property Model $collapsable
 */
class Collapse extends Model
{
    use SortableTrait;

    public $timestamps = false;

    protected $table = 'collapses';

    public function collapsable()
    {
        return $this->morphTo();
    }

    public static function orderColumnName(): string
    {
        return 'position';
    }
}
