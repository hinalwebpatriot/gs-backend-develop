<?php

namespace lenal\seo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Translatable\HasTranslations;

class SEOBlock extends Model
{
    protected $table = 'seo_blocks';
    public $timestamps = false;

    /**
     * @return MorphMany
     */
    public function collapses(): MorphMany
    {
        return $this->morphMany(Collapse::class, 'collapsable')->orderBy('position');
    }
}
