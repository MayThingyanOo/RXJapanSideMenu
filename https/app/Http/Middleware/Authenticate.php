<?php

namespace App\Http\Middleware;

use Closure;
use CpsAuth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = CpsAuth::setGuard($guard);
        if ($auth->isGuest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                $map = [
                    'user_staff' => ['get_login', ['redirect' => url()->current()]],
                ];
                return redirect()->guest(route($map[$guard][0], $map[$guard][1]));
            }
        }
        return $next($request);
    }
}
