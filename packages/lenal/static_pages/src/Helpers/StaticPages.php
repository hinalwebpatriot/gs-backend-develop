<?php

namespace lenal\static_pages\Helpers;

use lenal\static_pages\Models\StaticPage;
use lenal\static_pages\Resources\StaticPageResource;

class StaticPages
{
    public function getPage($code)
    {
        $page = StaticPage::whereCode($code)->first();
        return $page ? new StaticPageResource($page->makeHidden(['id', 'page', 'created_at', 'updated_at'])) : [];
    }
}
