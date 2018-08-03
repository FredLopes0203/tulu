<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;

/**
 * Class RouteNeedsRole.
 */
class RouteNeedsOrganization
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
            if(access()->user()->organization == 0)
            {
                return redirect()->route('admin.group.index')->withFlashDanger('Create or Choose your organization.');
            }
            else
            {
                $organizationInfo = Group::where('id', access()->user()->organization)->first();

                if($organizationInfo == null)
                {
                    return redirect()->route('admin.group.index')->withFlashDanger('Organization Deleted. Contact to the Administrator.');
                }
                else if( $organizationInfo->approved == 0)
                {
                    return redirect()->route('admin.group.index')->withFlashDanger('Yor organization is not approved yet.');
                }
                else if($organizationInfo->status == 0)
                {
                    return redirect()->route('admin.group.index')->withFlashDanger('Your organization is inactivated.');
                }

                if(access()->user()->approve == 0)
                {
                    return redirect()->route('admin.group.index')->withFlashDanger('Your admin permission is not approved yet.');
                }

                if(access()->user()->status == 0)
                {
                    return redirect()->route('admin.group.index')->withFlashDanger('Your account is inactivated.');
                }
            }
        }

        return $next($request);
    }
}
