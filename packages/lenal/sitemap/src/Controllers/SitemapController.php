<?php

namespace lenal\sitemap\Controllers;

use App\Http\Controllers\Controller;
use lenal\sitemap\Jobs\SitemapGenerateJob;
use Sitemap;

class SitemapController extends Controller
{
    public function index()
    {
        SitemapGenerateJob::dispatch();
    }
}
