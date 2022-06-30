<?php

namespace lenal\catalog\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class CatalogStore
 *
 * @package lenal\catalog\Middleware
 */
class CatalogStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->tokenIsValid($request)) {
            return response()->json([
                'message' => 'Permission denied',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function tokenIsValid(Request $request)
    {
        $catalog_token = config('catalog.store_token');
        $request_token = $request->header('X-DIAMOND-TOKEN');

        return !empty($catalog_token)
            && !empty($request_token)
            && $catalog_token === $request_token;
    }
}
