<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = array_keys(config('qreaget.locales'));
        $locale = $request->query('lang');
        if ($locale && in_array($locale, $locales, true)) {
            $request->session()->put('locale', $locale);
        }
        $locale = $request->session()->get('locale', $request->cookie('locale'));
        if (! in_array($locale, $locales, true)) {
            $locale = 'hy';
        }
        app()->setLocale($locale);

        return $next($request);
    }
}
