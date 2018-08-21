<?php

namespace App\Http\Controllers\Backend\Group;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Mail\AdminApproval;
use App\Mail\OrganizationApproval;
use App\Models\Access\User\User;
use App\Models\Group;
use App\Repositories\Backend\Group\GroupRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class GroupController extends Controller
{
    protected $groups;

    public function __construct(GroupRepository $groupsRepository)
    {
        $this->groups = $groupsRepository;
    }

    public function index(Request $request)
    {
        $groupID = access()->user()->organization;

        $groupInfo = Group::where('id', $groupID)->first();

        return view('backend.group.index')->with(['group' => $groupInfo]);
    }

    public function AssignShow(Request $request)
    {
        $user = access()->user();
        $groupID = $user->organization;
        $isInitial = $user->isinitial;

        if($groupID == 0 && $isInitial == 0)
        {
            return view('backend.group.assign');
        }
        else
        {
            return redirect()->back()->withFlashDanger(trans('auth.general_error'));
        }
    }

    public function create(Request $request)
    {
        $user = access()->user();
        $groupID = $user->organization;
        $isInitial = $user->isinitial;

        if($groupID == 0 && $isInitial == 0)
        {
            return view('backend.group.create');
        }
        else
        {
            return redirect()->back()->withFlashDanger(trans('auth.general_error'));
        }
    }

    public function edit($groupId, Request $request)
    {
        $user = access()->user();

        if($user->approve == 0)
        {
            throw new GeneralException('Your admin permission is not approved yet.');
        }

        if($user->organization != $groupId)
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $groupInfo = Group::where('id', $groupId)->first();

        return view('backend.group.edit')->with(['group' => $groupInfo]);
    }

    public function store(Request $request)
    {
        $organizationName = $request->input('organizationname');

        $url = "";
        $imageData = $request->input('imgData');

        if($imageData != null && $imageData != "")
        {
            $orgName = str_replace(' ', '', $organizationName);
            $fileName = $orgName . '.png';
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            File::put(base_path() . '/public/img/organization/'.$fileName, base64_decode($imageData));
            $url = 'img/organization/' . $fileName;
        }

        $newGroup = $this->groups->create(
            [
                'data' => $request->only(
                    'address1',
                    'address2',
                    'zip',
                    'country',
                    'state',
                    'city'
                ),
                'title' => $organizationName,
                'logoimg' => $url
            ]);

        //$adminContact = "jinyousheng727@outlook.com";
        $adminContact = "kurt.nguyen@qnexis.com";
        $newUser = access()->user();

        Mail::to($adminContact)->send(new OrganizationApproval($newUser, $newGroup));
        Mail::to($adminContact)->send(new AdminApproval($newUser, $newGroup));

        return redirect()->route('admin.group.index')->withFlashSuccess('Organization Created Successfully.');
    }

    public function AssignGroup(Request $request)
    {
        $organizationIDNum = $request->input('organizationid');

        $groupInfo = Group::where('groupid', $organizationIDNum)->first();

        if($groupInfo == null)
        {
            throw new GeneralException('Invalid organization ID#');
        }
        else
        {
            if($groupInfo->approved == 0)
            {
                throw new GeneralException('Not approved organization.');
            }
            else if($groupInfo->status == 0)
            {
                throw new GeneralException('Inactivated organization');
            }

            $user = access()->user();
            $user->organization = $groupInfo->id;
            $user->save();

            $adminContact = "kurt.nguyen@qnexis.com";

            Mail::to($adminContact)->send(new AdminApproval($user, $groupInfo));

            $initialAdmin = User::where('status', 1)
                        ->where('approve', 1)
                        ->where('isadmin', 1)
                        ->where('isinitial', 1)
                        ->where('organization', $groupInfo->id)
                        ->first();
            if($initialAdmin != null)
            {
                $initialadminContact = $initialAdmin->email;
                Mail::to($initialadminContact)->send(new AdminApproval($user, $groupInfo));
            }

            return redirect()->route('admin.group.index')->withFlashSuccess('Organization Assigned Successfully.');
        }
    }

    public function update(Group $group, Request $request)
    {
        $organizationName = $request->input('name');

//        $url = "";
//        if ($request->hasFile('logo')) {
//            $orgName = str_replace(' ', '', $organizationName);
//            $fileName = $orgName . '.png';
//
//            $request->file('logo')->move(
//                base_path() . '/public/img/organization/', $fileName
//            );
//            $url = 'img/organization/' . $fileName;
//        }

        $url = "";
        $imageData = $request->input('imgData');

        if($imageData != null && $imageData != "")
        {
            $orgName = str_replace(' ', '', $organizationName);
            $fileName = $orgName . '.png';
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            File::put(base_path() . '/public/img/organization/'.$fileName, base64_decode($imageData));
            $url = 'img/organization/' . $fileName;
        }

        $this->groups->update($group,
            [
                'data' => $request->only(
                    'address1',
                    'address2',
                    'zip',
                    'country',
                    'state',
                    'city'
                ),
                'title' => $organizationName,
                'logoimg' => $url
            ]);

        return redirect()->route('admin.group.index')->withFlashSuccess('Organization Updated Successfully.');
    }

    public function destroy(Group $group, Request $request)
    {
        $this->groups->delete($group);
        return redirect()->route('admin.group.index')->withFlashSuccess('Organization Removed Successfully.');
    }
}
