<?php

namespace GSD\Containers\Prerender\Jobs;

use GSD\Containers\Prerender\Components\PushPagesFromSitemap\PushPagesFromSitemapHandler;
use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\ResponseErrorException;
use GSD\Ship\Parents\Jobs\Job;

class PushPagesFromSitemapJob extends Job
{
    /**
     * @var array
     */
    private array $sitemapsUrl;

    /**
     * @param array $sitemapsUrl
     */
    public function __construct(array $sitemapsUrl)
    {
        $this->sitemapsUrl = $sitemapsUrl;
    }

    /**
     * @param PushPagesFromSitemapHandler $pushPagesFromSitemapHandler
     *
     * @return void
     * @throws ResponseErrorException
     */
    public function handle(PushPagesFromSitemapHandler $pushPagesFromSitemapHandler)
    {
        $pushPagesFromSitemapHandler->handle($this->sitemapsUrl);
    }
}