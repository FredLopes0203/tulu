<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Group;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $me = access()->user();
        $myOrganization = $me->organization;

        $organizationInfo = Group::where('id', $myOrganization)->first();

        $organizationName = "";

        if($organizationInfo != null)
        {
            $organizationName = $organizationInfo->name;
        }

        return view('frontend.user.account')->with(['organizationName' => $organizationName]);
    }
}
