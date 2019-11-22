<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header(`Access-Control-Allow-Origin`, `*`)
            ->header('Access-Control-Expose-Headers', 'Authorization')
            ->header('Access-Control-Request-Headers', 'Authorization')
            ->header('Access-Control-Request-Method', 'GET, POST, OPTIONS, PUT')
            ->header(`Access-Control-Allow-Methods`, `GET, POST, PUT, DELETE, OPTIONS`)
            ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
    }
}
