<?php

namespace App\Http\Controllers\Backend\Alert\Curalert;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Jobs\SendAlertCreated;
use App\Models\Access\User\User;
use App\Models\Alert;
use App\Models\UserLocation;
use App\Notifications\Backend\Alert\AlertCreated;
use App\Repositories\Backend\Alert\AlertRepository;
use Illuminate\Http\Request;

class CuralertController extends Controller
{
    protected $alerts;

    public function __construct(AlertRepository $alertRepository)
    {
        $this->alerts = $alertRepository;
    }

    public function index(Request $request)
    {
        return view('backend.alert.index');
    }

    public function create(Request $request)
    {
        $curuser = access()->user();
        $curOrganization = $curuser->organization;
        $currentAlert = Alert::where('organization', $curOrganization)
            ->where('mainalert', 0)
            ->where('status', 1)->first();

        if($currentAlert != null)
        {
            throw new GeneralException('There is active alert for your organization.');
        }
        return view('backend.alert.create');
    }

    public function update(Request $request)
    {
        $curuser = access()->user();
        $curOrganization = $curuser->organization;
        $currentAlert = Alert::where('organization', $curOrganization)
            ->where('mainalert', 0)
            ->where('status', 1)->first();

        if($currentAlert == null)
        {
            throw new GeneralException('There is no active alert for your organization.');
        }

        return view('backend.alert.update')->withAlert($currentAlert);
    }

    public function dismiss(Request $request)
    {
        $curuser = access()->user();
        $curOrganization = $curuser->organization;
        $currentAlert = Alert::where('organization', $curOrganization)
            ->where('mainalert', 0)
            ->where('status', 1)->first();

        if($currentAlert == null)
        {
            throw new GeneralException('There is no active alert for your organization.');
        }

        return view('backend.alert.dismiss')->withAlert($currentAlert);
    }

    public function newalert(Request $request)
    {
        $alertTitle = $request->input('alerttitle');
        $alertContent = $request->input('alertcontent');

        $push = $request->input('push');
        if($push == null)
        {
            $push = 0;
        }

        $text = $request->input('text');
        if($text == null)
        {
            $text = 0;
        }

        $email = $request->input('email');
        if($email == null)
        {
            $email = 0;
        }

        $response = $request->input('response');
        if($response == null)
        {
            $response = 0;
        }

        $alert = $this->alerts->create(
            [
                'title' => $alertTitle,
                'content' => $alertContent,
                'push' => $push,
                'text' => $text,
                'email' => $email,
                'response' => $response
            ]);

        dispatch(new SendAlertCreated($alert));

        return redirect()->route('admin.alert.curalert.index')->withFlashSuccess('Alert Created Successfully.');
    }

    public function store(Request $request)
    {
        $alertTitle = $request->input('alerttitle');
        $alertContent = $request->input('alertcontent');
        $mainalert = $request->input('mainalert');

//        $push = $request->input('push');
//        if($push == null)
//        {
//            $push = 0;
//        }
//
//        $text = $request->input('text');
//        if($text == null)
//        {
//            $text = 0;
//        }
//
//        $email = $request->input('email');
//        if($email == null)
//        {
//            $email = 0;
//        }
//
//        $response = $request->input('response');
//        if($response == null)
//        {
//            $response = 0;
//        }

        $alert = $this->alerts->update(
            [
                'mainalert' => $mainalert,
                'title' => $alertTitle,
                'content' => $alertContent//,
//                'push' => $push,
//                'text' => $text,
//                'email' => $email,
//                'response' => $response
            ]);
        dispatch(new SendAlertCreated($alert));
        return redirect()->route('admin.alert.curalert.index')->withFlashSuccess('Alert Updated Successfully.');
    }

    public function destroy(Request $request)
    {
        $alertTitle = $request->input('alerttitle');
        $alertContent = $request->input('alertcontent');
        $mainalert = $request->input('mainalert');

//        $push = $request->input('push');
//        if($push == null)
//        {
//            $push = 0;
//        }
//
//        $text = $request->input('text');
//        if($text == null)
//        {
//            $text = 0;
//        }
//
//        $email = $request->input('email');
//        if($email == null)
//        {
//            $email = 0;
//        }
//
//        $response = $request->input('response');
//        if($response == null)
//        {
//            $response = 0;
//        }

        $alert = $this->alerts->dismiss(
            [
                'mainalert' => $mainalert,
                'title' => $alertTitle,
                'content' => $alertContent
//                ,
//                'push' => $push,
//                'text' => $text,
//                'email' => $email,
//                'response' => $response
            ]);
        dispatch(new SendAlertCreated($alert));
        return redirect()->route('admin.alert.curalert.index')->withFlashSuccess('Alert Dismissed Successfully.');
    }

    public function locationview($userid, Request $request)
    {
        $location = UserLocation::where('userid', $userid)->first();
        if($location != null)
        {
            return view('backend.alert.tabs.viewresponse')->withLat($location->lat)->withLong($location->lng);
        }
        else
        {
            return view('backend.alert.tabs.viewresponse')->withLat("0")->withLong("0");
        }

    }
}
