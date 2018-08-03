<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Group;
use App\Repositories\Backend\Alert\AlertRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
/**
 * Class RoleController.
 */
class ApiAlertController extends Controller
{
    protected $alerts;

    public function __construct(AlertRepository $alertRepository)
    {
        $this->alerts = $alertRepository;
    }

    public function loadAlert(User $user, Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $currentOrganization = $user->organization;

        $resAlerts = $this->alerts->getAlerts($currentOrganization);

        return response()->json(['result' => true, 'alerts' => $resAlerts]);
    }

    public function checkCreateAbility(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $currentOrganization = $user->organization;

        $res = $this->alerts->checkCreate($currentOrganization);

        if($res == "TRUE")
        {
            return response()->json(['result' => true]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => $res]);
        }
    }

    public function checkUpdateAbility(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $currentOrganization = $user->organization;

        $res = $this->alerts->checkUpdate($currentOrganization);

        if($res == "TRUE")
        {
            $curAlert = $this->alerts->getCurAlert($currentOrganization);

            return response()->json(['result' => true, 'alertInfo' => $curAlert]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => $res]);
        }
    }

    public function createAlert(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $res = $this->alerts->createAlert($user, $input);

        if($res == "TRUE")
        {
            return response()->json(['result' => true]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => $res]);
        }
    }

    public function updateAlert(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $alertID = $input['id'];
        $title = $input['title'];
        $content = $input['content'];

        $res = $this->alerts->updateAlert($user, $alertID, $title, $content);

        if($res == "TRUE")
        {
            return response()->json(['result' => true]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => $res]);
        }
    }

    public function dismissAlert(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $alertID = $input['id'];
        $title = $input['title'];
        $content = $input['content'];

        $res = $this->alerts->dismissAlert($user, $alertID, $title, $content);

        if($res == "TRUE")
        {
            return response()->json(['result' => true]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => $res]);
        }
    }

    public function loadUserAlert(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $currentOrganization = $user->organization;

        $resAlerts = $this->alerts->getAlerts($currentOrganization);

        return response()->json(['result' => true, 'alerts' => $resAlerts]);
    }

    public function responseUserAlert(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $user = JWTAuth::toUser($token);

        $alertid = $input['alertid'];
        $response = $input['response'];

        $res = $this->alerts->responseAlert($user->id, $alertid, $response);

        if($res)
        {
            return response()->json(['result' => true]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => "You've already responded to this alert!"]);
        }
    }


    function loadAlertResponse(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        $type = $input['type'];
        $user = JWTAuth::toUser($token);

        $res = $this->alerts->getResponses($user, $type);

        return response()->json(['result' => true, 'responses' => $res]);
    }
}
