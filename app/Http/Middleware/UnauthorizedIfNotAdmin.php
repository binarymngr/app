<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

final class UnauthorizedIfNotAdmin
{
    /**
     * @{inherit}
     */
    public function handle(Request $rqst, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($rqst);
        }
        return abort(401);
    }
}
