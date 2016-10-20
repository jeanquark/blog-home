<?php namespace App\Http\Middleware;

use Closure;
use Sentinel;

class SentinelUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    public function handle($request, Closure $next) {
        if (Sentinel::check()) {
            return $next($request);
        } else {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
    }
}