<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(access()->user()->organization == 0)
        {
            return redirect()->route('admin.group.index');
        }
        return redirect()->route('admin.dashboard');
    }
}
