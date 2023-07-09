<?php

namespace App\Http\Middleware;

use Closure;

class PreventBackHistory
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // ref: https://stackoverflow.com/a/47432483
        $headers = [
            'Cache-Control' => 'nocache, no-store, max-age=0, must-revalidate',
            'Pragma', 'no-cache',
            'Expires', 'Fri, 01 Jan 1990 00:00:00 GMT',
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
