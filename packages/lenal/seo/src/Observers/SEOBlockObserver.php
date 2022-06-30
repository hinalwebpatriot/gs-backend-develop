<?php

namespace lenal\seo\Observers;

use lenal\seo\Models\Meta;
use lenal\seo\Models\SEOBlock;

/**
 * Class MetaBlockObserver
 *
 * @package lenal\seo\Observers
 */
class SEOBlockObserver
{
    /**
     * @param SEOBlock $model
     */
    public function saved(SEOBlock $model)
    {
        $meta = Meta::query()->where('page', $model->page);
        if ($meta) {
            $meta->update(['updated_at' => now()]);
        }
    }
}
