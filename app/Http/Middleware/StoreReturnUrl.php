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

            session(['return_url' => url()->previous()]);

            if (str_contains(session('return_url'), 'editProfile')) {
                session()->forget('return_url');
            }

        }

        return $next($request);
    }
}
