<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @param  null  $redirect
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $redirect = null)
    {
        if (Auth::guard($guard)->check()) {
            return $request->wantsJson()
                ? new JsonResponse([], 204)
                : redirect()->intended($redirect ?? RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
