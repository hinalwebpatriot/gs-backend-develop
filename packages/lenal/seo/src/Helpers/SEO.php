<?php

namespace lenal\seo\Helpers;

use Illuminate\Http\Response;
use lenal\seo\Models\Meta;
use lenal\seo\Models\SEOBlock;
use lenal\seo\Resources\SEOBlockResource;
use lenal\seo\Resources\SEOMetaResource;

class SEO
{
    public function getSEOBlock($page)
    {
        $block = SEOBlock::wherePage($page)->first();
        return $block
            ? (new SEOBlockResource($block))
            : response()->noContent();
    }

    public function getMeta($page)
    {
        $meta = Meta::wherePage($page)->first();
        return $meta
            ? new SEOMetaResource($meta)
            : null;
    }
}
