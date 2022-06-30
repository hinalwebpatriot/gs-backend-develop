<?php

namespace lenal\seo\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use lenal\seo\Facades\SEO;
use lenal\seo\Models\SeoRedirect;

class SEOController extends Controller
{
    public function pageBlocks($page)
    {
        return SEO::getSEOBlock($page);
    }

    public function meta($page)
    {
        return ($meta = SEO::getMeta($page))
            ? response()->json($meta)
            : response()->noContent();
    }

    public function catalogMeta($page)
    {
        return ($meta = SEO::getMeta($page))
            ? response()->json($meta)
            : response()->noContent();
    }

    public function fetchRedirectUrl()
    {
        /** @var SeoRedirect $model */
        $model = SeoRedirect::query()->where('url', ltrim(request('url'), '/'))->first();

        return response()->json([
            'redirect_url' => $model ? $model->redirectUrl() : ''
        ]);
    }
}
