<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        return response()->json([
            'errors' => 'Unauthorized',
            'status' => 401
        ]);
    }
}
