<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || ! Auth::user()->is($request->route('user'))) {
            abort(404);
        }

        return $next($request);
    }
}
