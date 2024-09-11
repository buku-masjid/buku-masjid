<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Number;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app()->setLocale(session('lang', 'id'));
        Number::useLocale(config('app.locale'));

        return $next($request);
    }
}
