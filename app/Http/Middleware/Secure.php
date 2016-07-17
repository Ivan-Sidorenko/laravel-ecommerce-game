<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;

/**
 * Secure
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class Secure implements Middleware
{
    /**
     * Redirects any non-secure requests to their secure counterparts.
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}