<?php

namespace App\Repositories\Backend\Alert;

use App\Jobs\SendAlertCreated;
use App\Models\Access\User\User;
use App\Models\Alert;
use App\Models\AlertResponse;
use App\Models\UserLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Nexmo\Response;

/**
 * Class UserRepository.
 */
class AlertRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Alert::class;

    public function __construct()
    {

    }

    public function checkAlertIfExist($curOrg)
    {
        if($this->query()->where('organization', $curOrg)->where('status', 1)->first())
        {
            throw new GeneralException('There is already active alert.');
        }
    }

    public function checkAlertIfNotExist($curOrg)
    {
        if(!$this->query()->where('organization', $curOrg)->where('status', 1)->first())
        {
            throw new GeneralException('There is no active alert.');
        }
    }

    public function create(array $input)
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerttitle = $input['title'];
        $alertcontent = $input['content'];
        $alertpush = $input['push'];
        $alerttext = $input['text'];
        $alertemail = $input['email'];
        $alertresponse = $input['response'];

        $this->checkAlertIfExist($currentOrganization);

        $alert = self::MODEL;
        $alert = new $alert;

        $alert->mainalert = 0;
        $alert->alerttype = 1;
        $alert->title = $alerttitle;
        $alert->content = $alertcontent;
        $alert->organization = $currentOrganization;
        $alert->status = 1;
        $alert->creator = $currentUser->id;
//        $alert->push = $alertpush;
        $alert->text = $alerttext;
        $alert->email = $alertemail;
        $alert->response = $alertresponse;

        DB::transaction(function () use ($alert) {
            if ($alert->save()) {

            }
        });
        return $alert;
    }

    public function update(array $input)
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerttitle = $input['title'];
        $alertcontent = $input['content'];
//        $alertpush = $input['push'];
//        $alerttext = $input['text'];
//        $alertemail = $input['email'];
//        $alertresponse = $input['response'];
        $mainalert = $input['mainalert'];

        $this->checkAlertIfNotExist($currentOrganization);
        $mainalertInfo = $this->query()->where('id', $mainalert)->first();

        $alert = self::MODEL;
        $alert = new $alert;

        $alert->mainalert = $mainalert;
        $alert->alerttype = 2;
        $alert->title = $alerttitle;
        $alert->content = $alertcontent;
        $alert->organization = $currentOrganization;
        $alert->status = 1;
        $alert->creator = $currentUser->id;
        $alert->push = $mainalertInfo->push;
        $alert->text = $mainalertInfo->text;
        $alert->email = $mainalertInfo->email;
        $alert->response = $mainalertInfo->response;

        DB::transaction(function () use ($alert) {
            if ($alert->save()) {

            }
        });
        return $alert;
    }

    public function dismiss(array $input)
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerttitle = $input['title'];
        $alertcontent = $input['content'];
//        $alertpush = $input['push'];
//        $alerttext = $input['text'];
//        $alertemail = $input['email'];
//        $alertresponse = $input['response'];
        $mainalert = $input['mainalert'];

        $this->checkAlertIfNotExist($currentOrganization);

        $mainalertInfo = $this->query()->where('id', $mainalert)->first();

        $alert = self::MODEL;
        $alert = new $alert;

        $alert->mainalert = $mainalert;
        $alert->title = $alerttitle;
        $alert->alerttype = 3;
        $alert->content = $alertcontent;
        $alert->organization = $currentOrganization;
        $alert->status = 2;
        $alert->creator = $currentUser->id;
        $alert->push = $mainalertInfo->push;
        $alert->text = $mainalertInfo->text;
        $alert->email = $mainalertInfo->email;
        $alert->response = $mainalertInfo->response;

        DB::transaction(function () use ($alert, $currentOrganization) {
            if ($alert->save()) {
                $res = Alert::where('status', 1)->where('organization', $currentOrganization)->update(['status' => 2]);
            }
        });
        return $alert;
    }

    public function getForDataTable()
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerts = $this->query()->where('status', 1)->where('organization', $currentOrganization)->orderby('created_at', 'DESC')->get();
        return $alerts;
    }

    public function getForResponseDataTable($type)
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $curalert = $this->query()->where('status', 1)
                                    ->where('organization', $currentOrganization)
                                    ->where('mainalert', 0)
                                    ->orderby('created_at', 'DESC')->first();
        if($curalert != null)
        {
            $responses = AlertResponse::where('alertid', $curalert->id);

            if($type == "0")
            {
                $responses = $responses->where('response', 0);
            }
            else if($type == "1")
            {
                $responses = $responses->where('response', 1);
            }

            $responses = $responses->orderBy('created_at', 'DESC')->get();
            foreach ($responses as $responsee)
            {
                $responserid = $responsee->userid;
                $responserInfo = User::where('id', $responserid)->first();

                $responsee['username'] = "Unknown";
                $responsee['useremail'] = "Unknown";
                $responsee['phonenumber'] = "Unknown";
                $responsee['location'] = "Unknown";
                $responsee['profileimage'] = "<img src='".$responserInfo->getProfileImage()."' class='img-circle' style='max-width: 70px;' alt='User Image'/>"; //asset("img/profile/sample.png");//$responserInfo->profile_image;

                if($responserInfo != null)
                {
                    $responsee['username'] = $responserInfo->first_name." ".$responserInfo->last_name;
                    $responsee['useremail'] = $responserInfo->email;
                    $responsee['phonenumber'] = $responserInfo->phonenumber;
                    $userlocation = UserLocation::where('userid', $responserInfo->id)->first();
                    if($userlocation != null)
                    {
                        $responsee['location'] = $userlocation->lat.", ".$userlocation->lng;
                    }
                }

                if($responsee->response == 1)
                {
                    $responsee['responsestr'] = "OK";
                }
                else
                {
                    $responsee['responsestr'] = "NOT OK";
                }
            }
            return $responses;
        }
        else
        {
            return new Collection();
        }
    }

    public function getAlerts($curOrganization)
    {
        $alerts = $this->query()->where('status', 1)->where('organization', $curOrganization)->orderby('created_at', 'DESC')->get();
        if($alerts->count() == 0)
        {
            $previousAlert = $this->query()->where('organization', $curOrganization)->orderby('created_at', 'DESC')->first();
            if($previousAlert != null)
            {
                $mainAlert = $previousAlert->mainalert;
                if($mainAlert == 0)
                {
                    $alerts = $this->query()->where('id', $previousAlert->id)->orderby('created_at', 'DESC')->get();
                }
                else
                {
                    $alerts = $this->query()->where('mainalert', $mainAlert)->orwhere('id', $mainAlert)->orderby('created_at', 'DESC')->get();
                }
            }
        }

        foreach ($alerts as $alert)
        {
            $alert['creatorInfo'] = User::where('id', $alert->creator)->first();
        }

        return $alerts;
    }

    public function responseAlert($userid, $alertid, $response)
    {
        $alert = $this->query()->where('id', $alertid)->first();

        if($response == "ok")
        {
            $responseVal = 1;
        }
        else
        {
            $responseVal = 0;
        }

        $isexist = AlertResponse::where('userid', $userid)
                            ->where('alertid', $alert->id)
                            ->first();

        if($isexist == null)
        {
            $newresponse = AlertResponse::create([
                'alertid' => $alert->id,
                'userid' => $userid,
                'response' => $responseVal
            ]);

            return true;
        }
        else
        {
            if($isexist->response == $responseVal)
            {
                return false;
            }
            else
            {
                $isexist->response = $responseVal;
                $isexist->save();
                return true;
            }
        }
    }

    public function getResponses($user, $type)
    {
        $curalert = $this->query()->where('organization', $user->organization)->where('status', 1)->first();

        $alertid = 0;
        if($curalert == null)
        {
            $previousAlert = $this->query()->where('organization', $user->organization)->orderby('created_at', 'DESC')->first();

            if($previousAlert != null)
            {
                $alertid = $previousAlert->mainalert;
            }
        }
        else
        {
            if($curalert->mainalert == 0)
            {
                $alertid = $curalert->id;
            }
            else
            {
                $alertid = $curalert->mainalert;
            }
        }

        $responses = AlertResponse::where('alertid', $alertid);

        if($type == 1)
        {
            $responses = $responses->where('response', 1);
        }
        else if($type == 2)
        {
            $responses = $responses->where('response', 0);
        }

        $responses = $responses->orderBy('created_at', 'DESC')->get();

        foreach ($responses as $response)
        {
            $userinfo = User::where('id', $response->userid)->first();
            $userlocation = UserLocation::where('userid', $response->userid)->first();
            $response['userinfo'] = $userinfo;
            $response['locationInfo'] = $userlocation;
        }

        if($type == 3)
        {
            $currentAlert = Alert::where('id', $alertid)->first();

            if($currentAlert != null)
            {
                if($currentAlert->response == 1)
                {
                    $curOrgUsers = User::where('organization', $user->organization)
                        ->where('isadmin', 0)
                        ->where('status', 1)
                        ->where('approve', 1)
                        ->where('confirmed', 1)
                        ->get();

                    if($curOrgUsers->count() > 0)
                    {
                        foreach ($curOrgUsers as $curOrgUser)
                        {
                            $responded = $curOrgUser->isResponded($alertid);

                            if(!$responded)
                            {
                                $curUserLocation = UserLocation::where('userid', $curOrgUser->id)->first();
                                $responseModel = new Collection();
                                $responseModel['userinfo'] = $curOrgUser;
                                $responseModel['locationInfo'] = $curUserLocation;
                                $responseModel['alertid'] = $alertid;
                                $responseModel['userid'] = $curOrgUser->id;
                                $responseModel['response'] = "3";
                                $responseModel['id'] = 0;
                                $responses->add($responseModel);
                            }
                        }
                    }
                }
            }
        }
        return $responses;
    }

    public function checkCreate($curOrganization)
    {
        if($this->query()->where('organization', $curOrganization)->where('status', 1)->first())
        {
            return "There is already active alert. You can't create new alert before it's dismissed.";
        }

        return "TRUE";
    }

    public function checkUpdate($curOrganization)
    {
        if(!$this->query()->where('organization', $curOrganization)->where('status', 1)->first())
        {
            return "There is no active alert. You can update or dismiss alert after you created new one.";
        }

        return "TRUE";
    }

    public function createAlert($user, $input)
    {
        $currentOrganization = $user->organization;

        $alerttitle = $input['title'];
        $alertcontent = $input['content'];
        $alertpush = $input['push'];
        $alerttext = $input['text'];
        $alertemail = $input['email'];
        $alertresponse = $input['response'];

        if($this->query()->where('organization', $currentOrganization)->where('status', 1)->first())
        {
            return "There is already active alert. You can't create new alert before it's dismissed.";
        }

        $alert = self::MODEL;
        $alert = new $alert;

        $alert->mainalert = 0;
        $alert->alerttype = 1;
        $alert->title = $alerttitle;
        $alert->content = $alertcontent;
        $alert->organization = $currentOrganization;
        $alert->status = 1;
        $alert->creator = $user->id;
//        $alert->push = $alertpush;
        $alert->text = $alerttext;
        $alert->email = $alertemail;
        $alert->response = $alertresponse;

        DB::transaction(function () use ($alert) {
            if ($alert->save()) {
                dispatch(new SendAlertCreated($alert));
            }
        });
        return "TRUE";
    }

    public function updateAlert($user, $alertid, $title, $content)
    {
        $currentOrganization = $user->organization;

        $prevAlert = $this->query()->where('id', $alertid)->where('organization', $currentOrganization)->where('status', 1)->first();

        if($prevAlert == null)
        {
            return "There is no active alert. You can update alert after you created new one.";
        }

        $alert = self::MODEL;
        $alert = new $alert;

        $main = $prevAlert->mainalert;
        if($main == 0)
        {
            $main = $prevAlert->id;
        }

        $alert->mainalert = $main;
        $alert->alerttype = 2;
        $alert->title = $title;
        $alert->content = $content;
        $alert->organization = $currentOrganization;
        $alert->status = 1;
        $alert->creator = $user->id;
        $alert->text = $prevAlert->text;
        $alert->email = $prevAlert->email;
        $alert->response = $prevAlert->response;

        DB::transaction(function () use ($alert) {
            if ($alert->save()) {
                dispatch(new SendAlertCreated($alert));
            }
        });
        return "TRUE";
    }

    public function dismissAlert($user, $alertid, $title, $content)
    {
        $currentOrganization = $user->organization;

        $prevAlert = $this->query()->where('id', $alertid)->where('organization', $currentOrganization)->where('status', 1)->first();

        if($prevAlert == null)
        {
            return "There is no active alert. You can dismiss alert after you created new one.";
        }

        $alert = self::MODEL;
        $alert = new $alert;

        $main = $prevAlert->mainalert;
        if($main == 0)
        {
            $main = $prevAlert->id;
        }

        $alert->mainalert = $main;
        $alert->alerttype = 3;
        $alert->title = $title;
        $alert->content = $content;
        $alert->organization = $currentOrganization;
        $alert->status = 2;
        $alert->creator = $user->id;
        $alert->text = $prevAlert->text;
        $alert->email = $prevAlert->email;
        $alert->response = $prevAlert->response;

        DB::transaction(function () use ($alert, $currentOrganization) {
            if ($alert->save()) {
                $res = Alert::where('status', 1)->where('organization', $currentOrganization)->update(['status' => 2]);
                dispatch(new SendAlertCreated($alert));
            }
        });
        return "TRUE";
    }

    public function getCurAlert($curOrganization)
    {
        $res = $this->query()->where('organization', $curOrganization)->where('status', 1)->first();
        return $res;
    }

    public function getForHistoryDataTable()
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerts = $this->query()->where('mainalert', 0)->where('organization', $currentOrganization)->orderby('created_at', 'DESC')->get();
        return $alerts;
    }

    public function getForHistoryDetailDataTable($alertID)
    {
        $currentUser = access()->user();
        $currentOrganization = $currentUser->organization;

        $alerts = $this->query()->where('id', $alertID)->orwhere('mainalert', $alertID)->orderby('created_at', 'DESC')->get();
        return $alerts;
    }


//    public function update(Model $group, array $input)
//    {
//        $data = $input['data'];
//        $logoUrl = $input['logoimg'];
//
//        $group->address1 = $data['address1'];
//        $group->address2 = $data['address2'];
//        $group->city = $data['city'];
//        $group->zip = $data['zip'];
//        $group->state = $data['state'];
//        $group->country = $data['country'];
//
//        if($logoUrl != "" && $logoUrl != null)
//        {
//            $group->logo = $logoUrl;
//        }
//
//        DB::transaction(function () use ($group) {
//            if ($group->save()) {
//            }
//        });
//        return $group;
//    }
//
//    protected function checkGroupNameIfAvailable($group, $input)
//    {
//        if ($group->name != $input['name'])
//        {
//            if ($this->query()->where('name', '=', $input['name'])->first())
//            {
//                throw new GeneralException('Existing Organization Name used.');
//            }
//        }
//    }
//
//    protected function checkGroupNameIfExist($input)
//    {
//        if($this->query()->where('name', $input)->first())
//        {
//            throw new GeneralException('Existing Organization Name Used.');
//        }
//    }
//
//    protected function checkGroupInitialAdmin($groupId)
//    {
//        $initialAdmin = User::where('organization', $groupId)->where('isinitial', 1)->first();
//        if($initialAdmin == null)
//        {
//            return true;
//        }
//        return false;
//    }
//
//    public function delete(Group $group)
//    {
//        if ($group->delete()) {
//            event(new GroupDeleted($group));
//            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
//            foreach ($admins as $admin)
//            {
//                $admin->notify(new OrganizationDeleteChanged(0, $group));
//            }
//            return true;
//        }
//        throw new GeneralException('Error in removing organization.');
//    }
//
//    public function forceDelete(Group $organization)
//    {
//        if (is_null($organization->deleted_at)) {
//            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
//        }
//
//        DB::transaction(function () use ($organization) {
//            if ($organization->forceDelete()) {
//                $admins = User::where('organization', $organization->id)->where('isadmin', 1)->get();
//                foreach ($admins as $admin)
//                {
//                    $admin->notify(new OrganizationDeleteChanged(2, $organization));
//                }
//                return true;
//            }
//
//            throw new GeneralException('Error in organization permanently deleting.');
//        });
//    }
//
//    public function restore(Group $organization)
//    {
//        if (is_null($organization->deleted_at)) {
//            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
//        }
//
//        if ($organization->restore()) {
//
//            $admins = User::where('organization', $organization->id)->where('isadmin', 1)->get();
//            foreach ($admins as $admin)
//            {
//                $admin->notify(new OrganizationDeleteChanged(1, $organization));
//            }
//            return true;
//        }
//
//        throw new GeneralException('Error in organization restoring.');
//    }
//
//    public function mark(Group $group, $status)
//    {
//        $group->status = $status;
//
//        if ($group->save()) {
//            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
//            foreach ($admins as $admin)
//            {
//                $admin->notify(new OrganizationStatusChanged($status, $group));
//            }
//            return true;
//        }
//
//        throw new GeneralException('Organization Updating Error');
//    }
//
//    public function approve(Group $group, $approve)
//    {
//        $group->approved = $approve;
//
//        if($approve == 1)
//        {
//            if($group->groupid == "" || $group->groupid == null)
//            {
//                $group->groupid = $this->generateRndString(5);
//            }
//        }
//
//        if ($group->save()) {
//            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
//            foreach ($admins as $admin)
//            {
//                $admin->notify(new OrganizationApproveChanged($approve, $group));
//            }
//            return true;
//        }
//
//        throw new GeneralException('Error in approval.');
//    }
//
//    public function getForDataTable($status = 1, $approve = 1, $trashed = false)
//    {
//        if ($trashed == 'true') {
//
//            $groups = $this->query();
//            $groups = $groups->onlyTrashed();
//
//            return $groups;
//        }
//        else
//        {
//            if($status == 0)
//            {
//                $groups = $this->query()->where('status', $status)->orderby('created_at', 'ASC')->get();
//            }
//            else
//            {
//                $groups = $this->query()->where('status', $status)->where('approved', $approve)->orderby('created_at', 'ASC')->get();
//            }
//            return $groups;
//        }
//    }
//
//    function generateRndString($length = 5) {
//        $characters = '1234567890';
//        $charactersLength = strlen($characters);
//        $randomString = '';
//        for ($i = 0; $i < $length; $i++) {
//            $randomString .= $characters[rand(0, $charactersLength - 1)];
//        }
//
//        return $randomString;
//    }
}
