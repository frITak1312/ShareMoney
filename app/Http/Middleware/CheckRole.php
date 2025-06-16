<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $account = $request->route('account');
        $user = auth()->user();
        if (! $account) {
            abort(404, 'Účet nenalezen.');
        }

        $membership = $account->getUserRole($user->id);
        if (! $role) {
            abort(403, 'Nejste členem tohoto účtu.');
        }

        if ($membership !== $role) {
            abort(403, 'Nemáte oprávnění k této akci.');
        }

        return $next($request);
    }
}
