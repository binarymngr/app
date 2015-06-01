<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

/**
 * Before middleware to redirect the user to the login page if he's not logged-in.
 */
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
