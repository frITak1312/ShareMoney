<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckIdentity
{
    public function handle(Request $request, Closure $next, User $user)
    {
        if ($user !== auth()->user()) {
            abort(403, 'Nemáte oprávnění k zobrazení tohoto profilu.');
        }

        return $next($request);
    }
}
