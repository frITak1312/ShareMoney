<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class StoreReturnUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        // Ukládám návratovou URL jen pro GET požadavky a jen mimo edit profil
        if ($request->isMethod('get') &&
            ! $request->is('editProfile/*') &&
            ! $request->ajax()) {

            session(['return_url' => url()->full()]);
        }

        return $next($request);
    }
}
