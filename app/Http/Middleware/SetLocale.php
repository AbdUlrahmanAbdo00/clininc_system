<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->header('lang') ?? $request->query('lang') ?? 'en';

        if (in_array($lang, ['en', 'ar'])) {
            App::setLocale($lang);
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}

