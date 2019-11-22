<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
        public function handle($request, Closure $next, ...$guards)
        {
            if ($this->authenticate($request, $guards) === 'authentication_failed') {
                return response()->json(['error'=>'Unauthorized'],400);
            }
            return $next($request);
        }
        // Override authentication method
        protected function authenticate($request, array $guards)
        {
            if (empty($guards)) {
                $guards = [null];
            }
            foreach ($guards as $guard) {
                if ($this->auth->guard($guard)->check()) {
                    return $this->auth->shouldUse($guard);
                }
            }
            return 'authentication_failed';
        }
}
