<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if(access()->hasRole(2))
        {
            if(access()->user()->organization == 0)
            {
                return redirect()->route('admin.group.index');
            }
        }

        return view('backend.dashboard');
    }

    public function adminindex()
    {
        if(access()->hasRole(2))
        {
            if(access()->user()->organization == 0)
            {
                return redirect()->route('admin.group.index');
            }
        }
        return redirect()->route('admin.dashboard');
    }
}
