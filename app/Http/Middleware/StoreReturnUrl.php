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
        // Ukládám návratovou URL jen pro GET požadavky a mimo auth stránky
        if ($request->isMethod('get') &&
            ! $request->is('login') &&
            ! $request->is('register') &&
            ! $request->is('/') &&
            ! $request->ajax()) {

            // Pokud jsme na editProfile, neukládáme URL
            if (! $request->is('editProfile/*')) {
                session(['return_url' => url()->full()]);
            }
        }

        // Pokud nemáme return_url, nastavíme dashboard
        if (! session()->has('return_url')) {
            session(['return_url' => route('dashboardPage')]);
        }

        return $next($request);
    }
}
