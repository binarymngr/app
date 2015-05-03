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
        if ($response->original->isVisibleToUser($user)) {
            return $response;
        }
        return response()->json([
            'errors' => 'Unauthorized',
            'status' => 401
        ]);
    }
}
