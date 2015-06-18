<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

/**
 * Before middleware that raises a 401 Unauthorized error if the request user
 * is not an administrator.
 */
final class UnauthorizedIfNotAdmin
{
    /**
     * @{inherit}
     */
    public function handle(Request $rqst, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(401);
        }
        return $next($rqst);
    }
}
