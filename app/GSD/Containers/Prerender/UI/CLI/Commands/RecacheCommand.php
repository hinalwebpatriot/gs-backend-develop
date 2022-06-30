<?php


namespace GSD\Containers\Prerender\UI\CLI\Commands;


use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\ResponseErrorException;
use GSD\Containers\Prerender\Components\PrerenderClient\Exceptions\TooMuchUrlsException;
use GSD\Containers\Prerender\Components\RecachePages\RecacheUrlsHandler;
use GSD\Ship\Parents\Commands\Command;
use Illuminate\Support\Facades\App;

/**
 * Recache selected pages on demand.
 *
 * Class RecacheCommand
 * @package GSD\Containers\Prerender\UI\CLI\Commands
 */
class RecacheCommand extends Command
{
    private const PRESELECTED_PAGES = [
        '/diamonds/',
        '/diamonds/round',
        '/diamonds/princess',
        '/diamonds/cushion',
        '/diamonds/oval',
        '/diamonds/emerald',
        '/diamonds/pear',
        '/diamonds/asscher',
        '/diamonds/radiant',
        '/diamonds/marquise',
        '/diamonds/heart',
        '/diamonds/0-5-carat',
        '/diamonds/1-carat',
        '/diamonds/1-5-carat',
        '/diamonds/1-8-carat',
        '/diamonds/2-carat',
        '/diamonds/3-carat',
        '/diamonds/4-carat',
        '/diamonds/5-carat',
        '/diamonds/6-carat',
        '/diamonds/6-6-carat',
        '/diamonds/7-carat',
        '/diamonds/7-25-carat',
        '/diamonds/7-5-carat',
        '/diamonds/8-carat',
        '/diamonds/9-carat',
        '/diamonds/10-carat',
        '/diamonds/11-carat',
        '/brisbane/diamonds/',
        '/brisbane/diamonds/0-5-carat',
        '/brisbane/diamonds/1-carat',
        '/brisbane/diamonds/1-5-carat',
        '/brisbane/diamonds/1-8-carat',
        '/brisbane/diamonds/2-carat',
        '/brisbane/diamonds/3-carat',
        '/brisbane/diamonds/4-carat',
        '/brisbane/diamonds/5-carat',
        '/brisbane/diamonds/6-carat',
        '/brisbane/diamonds/6-6-carat',
        '/brisbane/diamonds/7-carat',
        '/brisbane/diamonds/7-25-carat',
        '/brisbane/diamonds/7-5-carat',
        '/brisbane/diamonds/8-carat',
        '/brisbane/diamonds/9-carat',
        '/brisbane/diamonds/10-carat',
        '/brisbane/diamonds/11-carat',
        '/brisbane/diamonds/round',
        '/brisbane/diamonds/princess',
        '/brisbane/diamonds/cushion',
        '/brisbane/diamonds/oval',
        '/brisbane/diamonds/pear',
        '/brisbane/diamonds/asscher',
        '/brisbane/diamonds/radiant',
        '/brisbane/diamonds/marquise',
        '/brisbane/diamonds/heart',
        '/melbourne/diamonds/',
        '/melbourne/diamonds/0-5-carat',
        '/melbourne/diamonds/1-carat',
        '/melbourne/diamonds/1-5-carat',
        '/melbourne/diamonds/1-8-carat',
        '/melbourne/diamonds/2-carat',
        '/melbourne/diamonds/3-carat',
        '/melbourne/diamonds/4-carat',
        '/melbourne/diamonds/5-carat',
        '/melbourne/diamonds/6-carat',
        '/melbourne/diamonds/6-6-carat',
        '/melbourne/diamonds/7-carat',
        '/melbourne/diamonds/7-25-carat',
        '/melbourne/diamonds/7-5-carat',
        '/melbourne/diamonds/8-carat',
        '/melbourne/diamonds/9-carat',
        '/melbourne/diamonds/10-carat',
        '/melbourne/diamonds/11-carat',
        '/melbourne/diamonds/round',
        '/melbourne/diamonds/princess',
        '/melbourne/diamonds/cushion',
        '/melbourne/diamonds/oval',
        '/melbourne/diamonds/pear',
        '/melbourne/diamonds/asscher',
        '/melbourne/diamonds/radiant',
        '/melbourne/diamonds/marquise',
        '/melbourne/diamonds/heart',
        '/perth/diamonds/',
        '/perth/diamonds/0-5-carat',
        '/perth/diamonds/1-carat',
        '/perth/diamonds/1-5-carat',
        '/perth/diamonds/1-8-carat',
        '/perth/diamonds/2-carat',
        '/perth/diamonds/3-carat',
        '/perth/diamonds/4-carat',
        '/perth/diamonds/5-carat',
        '/perth/diamonds/6-carat',
        '/perth/diamonds/6-6-carat',
        '/perth/diamonds/7-carat',
        '/perth/diamonds/7-25-carat',
        '/perth/diamonds/7-5-carat',
        '/perth/diamonds/8-carat',
        '/perth/diamonds/9-carat',
        '/perth/diamonds/10-carat',
        '/perth/diamonds/11-carat',
        '/perth/diamonds/round',
        '/perth/diamonds/princess',
        '/perth/diamonds/cushion',
        '/perth/diamonds/oval',
        '/perth/diamonds/pear',
        '/perth/diamonds/asscher',
        '/perth/diamonds/radiant',
        '/perth/diamonds/marquise',
        '/perth/diamonds/heart',
        '/adelaide/diamonds/',
        '/adelaide/diamonds/0-5-carat',
        '/adelaide/diamonds/1-carat',
        '/adelaide/diamonds/1-5-carat',
        '/adelaide/diamonds/1-8-carat',
        '/adelaide/diamonds/2-carat',
        '/adelaide/diamonds/3-carat',
        '/adelaide/diamonds/4-carat',
        '/adelaide/diamonds/5-carat',
        '/adelaide/diamonds/6-carat',
        '/adelaide/diamonds/6-6-carat',
        '/adelaide/diamonds/7-carat',
        '/adelaide/diamonds/7-25-carat',
        '/adelaide/diamonds/7-5-carat',
        '/adelaide/diamonds/8-carat',
        '/adelaide/diamonds/9-carat',
        '/adelaide/diamonds/10-carat',
        '/adelaide/diamonds/11-carat',
        '/adelaide/diamonds/round',
        '/adelaide/diamonds/princess',
        '/adelaide/diamonds/cushion',
        '/adelaide/diamonds/oval',
        '/adelaide/diamonds/pear',
        '/adelaide/diamonds/asscher',
        '/adelaide/diamonds/radiant',
        '/adelaide/diamonds/marquise',
        '/adelaide/diamonds/heart',
        '/canberra/diamonds/',
        '/canberra/diamonds/0-5-carat',
        '/canberra/diamonds/1-carat',
        '/canberra/diamonds/1-5-carat',
        '/canberra/diamonds/1-8-carat',
        '/canberra/diamonds/2-carat',
        '/canberra/diamonds/3-carat',
        '/canberra/diamonds/4-carat',
        '/canberra/diamonds/5-carat',
        '/canberra/diamonds/6-carat',
        '/canberra/diamonds/6-6-carat',
        '/canberra/diamonds/7-carat',
        '/canberra/diamonds/7-25-carat',
        '/canberra/diamonds/7-5-carat',
        '/canberra/diamonds/8-carat',
        '/canberra/diamonds/9-carat',
        '/canberra/diamonds/10-carat',
        '/canberra/diamonds/11-carat',
        '/canberra/diamonds/round',
        '/canberra/diamonds/princess',
        '/canberra/diamonds/cushion',
        '/canberra/diamonds/oval',
        '/canberra/diamonds/pear',
        '/canberra/diamonds/asscher',
        '/canberra/diamonds/radiant',
        '/canberra/diamonds/marquise',
        '/canberra/diamonds/heart',
        '/melbourne/diamonds/emerald',
        '/perth/diamonds/emerald',
        '/adelaide/diamonds/emerald',
        '/canberra/diamonds/emerald',
        '/brisbane/diamonds/emerald',
    ];

    protected $signature   = 'prerender:recache:featured';
    protected $description = 'Trigger recache of predefined pages in prerender';

    /**
     * @throws ResponseErrorException|TooMuchUrlsException
     */
    public function handle(RecacheUrlsHandler $recacheUrlsHandler)
    {
        if (!App::environment('production')
            && !$this->confirm('This command designed to run on the production only. Are you sure?')
        ) {
            return;
        }

        $pages = [];

        foreach (self::PRESELECTED_PAGES as $page) {
            $pages[] = sprintf('%s%s', config('app.frontend_url'), $page);
        }

        $recacheUrlsHandler->handle($pages);
    }
}
