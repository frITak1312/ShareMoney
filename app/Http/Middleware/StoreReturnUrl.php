<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreReturnUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Seznam cest, které nechceme ukládat
        $excludedPaths = [
            'login',
            'register',
            'logout',
            'profileDetail',
            'memberManagement',
        ];

        // Ukládám návratovou URL jen pro GET požadavky a mimo vyloučené cesty
        if ($request->isMethod('get') &&
            ! $request->ajax() &&
            ! $this->isExcludedPath($request, $excludedPaths)) {

            session(['return_url' => url()->full()]);
        }

        return $next($request);
    }

    private function isExcludedPath(Request $request, array $excludedPaths)
    {
        foreach ($excludedPaths as $path) {
            if ($request->is($path) || str_contains($request->path(), $path)) {
                return true;
            }
        }

        return false;
    }
}
