<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class APIAuthMiddleware
 * @package App\Http\Middleware
 */
class APIAuthMiddleware
{

    /**
     * Auth an incoming Api request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('apikey') != env('API_KEY', '')) { //Header auth
            if ($request->only('apikey')['apikey'] != env('API_KEY', '')) { //query string auth
                abort(403, 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
