<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class NonAuthenticate
{
    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    public function handle(Request $request, Closure $next)
    {
        return Auth::check() ? redirect(RouteServiceProvider::HOME) : $next($request);
    }
}
