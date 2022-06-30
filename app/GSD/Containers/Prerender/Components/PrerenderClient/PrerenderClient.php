<?php

namespace GSD\Containers\Prerender\Components\PrerenderClient;

use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\ResponseErrorException;
use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\TooMuchUrlsException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * https://docs.prerender.io/article/6-api
 */
class PrerenderClient
{
    const PRERENDER_API_URL         = 'https://api.prerender.io';
    const PRERENDER_API_SITEMAP_URL = '/sitemap';
    const PRERENDER_API_RECACHE_URL = '/recache';

    const MAX_URLS_TO_RECACHE = 1000;

    /**
     * @var string
     */
    private string $apiToken;

    /**
     * @var PendingRequest
     */
    private PendingRequest $client;

    /**
     * @param string $apiToken
     */
    public function __construct(string $apiToken)
    {
        $this->client = Http::baseUrl(self::PRERENDER_API_URL)->asJson();
        $this->apiToken = $apiToken;
    }

    /**
     * The sitemap api lets you to add new URLs via sitemap XML files.
     * Existing URLs will not be recached.
     *
     * @param string $url
     *
     * @throws ResponseErrorException
     */
    public function sitemap(string $url)
    {
        $response = $this->client->post(self::PRERENDER_API_SITEMAP_URL, [
            'url'            => $url,
            'prerenderToken' => $this->apiToken,
        ]);

        if (!$response->successful()) {
            throw new ResponseErrorException(
                'Prerender sitemap response error', 0, $response->toException()
            );
        }
    }

    /**
     * The recache API can take up to 1,000 URLs per request.
     * Make sure to change the parameter to "urls" if you want to add more than 1 url per request.
     *
     * @param array $urls
     *
     * @throws ResponseErrorException
     * @throws TooMuchUrlsException
     */
    public function recache(array $urls)
    {
        if (count($urls) > self::MAX_URLS_TO_RECACHE) {
            throw new TooMuchUrlsException('Max of 1000 urls allowed');
        }

        $response = $this->client->post(self::PRERENDER_API_RECACHE_URL, [
            'urls'           => $urls,
            'prerenderToken' => $this->apiToken,
        ]);

        if (!$response->successful()) {
            throw new ResponseErrorException(
                'Prerender recache response error', 0, $response->toException()
            );
        }
    }
}