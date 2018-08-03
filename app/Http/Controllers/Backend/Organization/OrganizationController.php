<?php

namespace App\Http\Controllers\Backend\Organization;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Repositories\Backend\Group\GroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class OrganizationController extends Controller
{
    protected $groups;

    public function __construct(GroupRepository $groupsRepository)
    {
        $this->groups = $groupsRepository;
    }

    public function index(Request $request)
    {
        return view('backend.organization.index');
    }

    public function getPending(Request $request)
    {
        return view('backend.organization.pending');
    }

    public function getDeactivated(Request $request)
    {
        return view('backend.organization.deactivated');
    }

    public function getDeleted(Request $request)
    {
        return view('backend.organization.deleted');
    }

    public function create(Request $request)
    {
        return view('backend.group.create');
    }

    public function edit(Group $organization, Request $request)
    {
        return view('backend.group.edit')->withGroup($organization);
    }

    public function store(Request $request)
    {
        $organizationName = $request->input('organizationname');

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

        $this->groups->create(
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
        return redirect()->route('admin.organization.index')->withFlashSuccess('Organization Created Successfully.');
    }

    public function update(Group $organization, Request $request)
    {
        $organizationName = $request->input('name');

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

        $this->groups->update($organization,
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

        if($organization->status == 0)
        {
            return redirect()->route('admin.organization.deactivated')->withFlashSuccess('Organization Updated Successfully.');
        }
        else
        {
            if($organization->approved == 0)
            {
                return redirect()->route('admin.organization.pending')->withFlashSuccess('Organization Updated Successfully.');
            }
            else
            {
                return redirect()->route('admin.organization.index')->withFlashSuccess('Organization Updated Successfully.');
            }
        }
    }

    public function destroy(Group $organization, Request $request)
    {
        $this->groups->delete($organization);

        return redirect()->route('admin.organization.deleted')->withFlashSuccess('Organization Deleted Successfully.');
    }

    public function delete(Group $organization, Request $request)
    {
        $this->groups->forceDelete($organization);
        return redirect()->route('admin.organization.deleted')->withFlashSuccess('Organization Deleted Permanently.');
    }

    public function restore(Group $organization, Request $request)
    {
        $this->groups->restore($organization);
        $status = $organization->status;
        $approve = $organization->approved;

        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.organization.index': 'admin.organization.pending' : 'admin.organization.deactivated')->withFlashSuccess('Organization Restored Successfully.');
    }

    public function mark(Group $group, $status, Request $request)
    {
        $approve = $group->approved;
        $this->groups->mark($group, $status);
        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.organization.index': 'admin.organization.pending' : 'admin.organization.deactivated')->withFlashSuccess('The organization was successfully updated.');
    }

    public function approve(Group $group, $approve, Request $request)
    {
        $this->groups->approve($group, $approve);
        return redirect()->route($approve == 1 ? 'admin.organization.index' : 'admin.organization.pending')->withFlashSuccess('The organization was successfully updated.');
    }

    public function show(Group $organization, Request $request)
    {
        return view('backend.organization.show')
            ->withOrganization($organization);
    }
}
