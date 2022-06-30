<?php

namespace GSD\Containers\Prerender\Components\RecachePages;

use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\ResponseErrorException;
use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\TooMuchUrlsException;
use GSD\Containers\Prerender\Components\PrerenderClient\PrerenderClient;

class RecacheUrlsHandler
{
    const RECACHE_LIMIT = 1000;

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
     * @param array $urls
     *
     * @return void
     * @throws ResponseErrorException
     * @throws TooMuchUrlsException
     */
    public function handle(array $urls)
    {
        $chunked = array_chunk($urls, self::RECACHE_LIMIT);

        foreach ($chunked as $urlChunk) {
            $this->prerenderClient->recache($urlChunk);
        }
    }
}