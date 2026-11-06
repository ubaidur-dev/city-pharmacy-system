<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supported = array_keys(config('locales'));
        $locale = $request->session()->get('locale') ?? $request->user()?->locale ?? config('app.locale');
        if (! in_array($locale, $supported, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        view()->share('dir', config("locales.$locale.1"));

        return $next($request);
    }
}
