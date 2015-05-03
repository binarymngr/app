<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

final class RedirectLoginIfNotLoggedIn
{
    /**
     * @{inherit}
     */
    public function handle(Request $rqst, Closure $next)
    {
        if (Auth::check()) {
            return $next($rqst);
        }
        return redirect()->route('login');
    }
}
