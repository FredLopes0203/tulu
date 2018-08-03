<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class RouteNeedsRole.
 */
class RouteNeedsOwner
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
        if(access()->hasRole(2))
        {
            if(access()->user()->isinitial == 0)
            {
                return redirect()->route('admin.group.index')->withFlashDanger(trans('auth.general_error'));
            }
        }

        return $next($request);
    }
}
