<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAuth
{
    /**
     * @{inherit}
     */
    public function handle(Request $rqst, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($rqst);
    }
}
