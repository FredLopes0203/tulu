<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
/**
 * Class RouteNeedsRole.
 */
class RouteForMobileUser
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
        $input = $request->all();

        $token = $input['token'];
        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result'=>false, 'message'=>'Token Required.']);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Expired.']);

        } catch (TokenInvalidException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Invalid']);
        }

        if(!$user)
        {
            return response()->json(['result'=>false, 'message'=>'Invalid user info.']);
        }

        if($user->confirmed == 0)
        {
            return response()->json(['result'=>false, 'message'=>'Your account is not confirmed!']);
        }

        if($user->status == 0)
        {
            return response()->json(['result'=>false, 'message'=>'Your account is not deactivated!']);
        }

        if($user->organization == 0)
        {
            return response()->json(['result'=>false, 'message'=>'You are not assigned to any organization!']);
        }
        else
        {
            $orgInfo = Group::where('id', $user->organization)->first();

            if($orgInfo == null)
            {
                return response()->json(['result'=>false, 'message'=>'Invalid Organization!']);
            }
            else
            {
                if($orgInfo->status == 0)
                {
                    return response()->json(['result'=>false, 'message'=>'Organization Deactivated!']);
                }

                if($orgInfo->approved == 0)
                {
                    return response()->json(['result'=>false, 'message'=>'Organization is not approved yet!']);
                }
            }
        }

        if($user->isadmin == 1)
        {
            return response()->json(['result'=>false, 'message'=>'You have no user permission!']);
        }

        if($user->approve == 0)
        {
            return response()->json(['result'=>false, 'message'=>'Your account is not approved by organization manager!']);
        }

        return $next($request);
    }
}
