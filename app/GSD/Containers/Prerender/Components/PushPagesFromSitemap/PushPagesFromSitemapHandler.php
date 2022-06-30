<?php

namespace GSD\Containers\Prerender\Components\PushPagesFromSitemap;

use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\ResponseErrorException;
use GSD\Containers\Prerender\Components\PrerenderClient\PrerenderClient;


class PushPagesFromSitemapHandler
{
    /**
     * @var PrerenderClient
     */
    private PrerenderClient $prerenderClient;

    /**
     * @param PrerenderClient $prerenderClient
     */
    public function __construct(PrerenderClient $prerenderClient)
    {
        $this->prerenderClient = $prerenderClient;
    }

    /**
     * @param array $sitemapsUrl
     *
     * @return void
     * @throws ResponseErrorException
     */
    public function handle(array $sitemapsUrl)
    {
        foreach ($sitemapsUrl as $sitemapUrl) {
            $this->prerenderClient->sitemap($sitemapUrl);
        }
    }
}