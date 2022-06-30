<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    private $locales = [
        'en',
        'ru',
        'zh',
        'yu',
        'cmn',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_language = $request->server('HTTP_ACCEPT_LANGUAGE');
        $locale = in_array($request_language, $this->locales, true)
            ? $request_language
            : 'en';

        app()->setLocale($locale);

        return $next($request);
    }
}
