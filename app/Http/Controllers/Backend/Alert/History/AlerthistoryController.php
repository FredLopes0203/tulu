<?php

namespace App\Http\Controllers\Backend\Alert\History;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

/**
 * Class UserController.
 */
class AlerthistoryController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        return view('backend.alert.history.index');
    }

    public function view($alertId, Request $request)
    {
        $curUser = access()->user();
        $alert = Alert::where('id', $alertId)->where('organization', $curUser->organization)->first();

        if($alert == null)
        {
            throw new GeneralException('Invalid Alert!');
        }

        return view('backend.alert.history.view')->withAlert($alert);
    }
}
