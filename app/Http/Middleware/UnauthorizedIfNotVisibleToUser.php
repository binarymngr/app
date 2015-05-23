<?php namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

final class UnauthorizedIfNotVisibleToUser
{
    /**
     * @{inherit}
     */
    public function handle(Request $rqst, Closure $next)
    {
        $user = Auth::user();
        $response = $next($rqst);
        if (method_exists($response->original, 'isVisibleToUser') && !$response->original->isVisibleToUser($user)) {
            abort(401);
        }
        return $response;
    }
}
