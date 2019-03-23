<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class MentorAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        // dd(Auth::guard("teacher")->guest());
        if (Auth::guard("mentor")->guest()) {
            // if ($request->ajax() || $request->wantsJson()) {
                // return response('Unauthorized.', 401);
            // } else {
                return redirect()->guest('mentor/login');
            // }
        }
        return $next($request);
    }
}
