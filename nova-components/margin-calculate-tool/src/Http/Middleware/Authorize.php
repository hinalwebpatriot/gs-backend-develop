<?php

namespace lenal\MarginCalculateTool\Http\Middleware;

use lenal\MarginCalculateTool\MarginCalculateTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return resolve(MarginCalculateTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
