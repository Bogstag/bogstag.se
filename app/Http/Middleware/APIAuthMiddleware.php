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
        if ($request->method() == 'POST' || $request->method() == 'PUT' || $request->method() == 'PATCH') {
            if ($request->header('apikey') != env('API_KEY', '')) {
                if (\Input::only('apikey') != env('API_KEY', '')) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        return $next($request);
    }
}
