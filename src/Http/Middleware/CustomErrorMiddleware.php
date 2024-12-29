<?php

namespace App\Http\Middleware;

class CustomErrorMiddleware
{
    public function handle($request, $next)
    {
        return $next($request);
    }
}
