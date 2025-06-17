<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIdentity
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->route('user');

        if ($user->id !== auth()->id()) {
            abort(403, 'Nemáte oprávnění k zobrazení tohoto profilu.');
        }

        return $next($request);
    }
}
