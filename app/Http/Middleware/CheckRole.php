<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $account = $request->route('account');
        $user = auth()->user();
        if (! $account) {
            abort(404, 'Účet nebo uživatel nenalezen.');
        }

        $membership = $account->getUserRole($user->id);
        if (! $membership) {
            abort(403, 'Nejste členem tohoto účtu.');
        } elseif (empty($role)) {
            return $next($request);
        }

        if (! in_array($membership->role, $roles)) {
            abort(403, 'Nemáte oprávnění k této akci.');
        }

        return $next($request);
    }
}
