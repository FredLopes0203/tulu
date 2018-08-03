<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class RouteNeedsRole.
 */
class RouteForAdmin
{
    /**
     * @param $request
     * @param Closure $next
     * @param $role
     * @param bool $needsAll
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access = access()->hasRole(2);
        $adminaccess = access()->hasRole(1);

        if ($access || $adminaccess) {
            return $next($request);
        }
        else
        {
            return redirect()
                ->route(homeRoute())
                ->withFlashDanger(trans('auth.general_error'));
        }
    }
}
