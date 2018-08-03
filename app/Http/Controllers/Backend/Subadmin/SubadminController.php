<?php

namespace App\Http\Controllers\Backend\Subadmin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Subadmin\Subadmin;
use App\Repositories\Backend\Subadmin\SubadminRepository;
use Illuminate\Http\Request;


class SubadminController extends Controller
{
    protected $subadmins;

    public function __construct(SubadminRepository $subadminRepository)
    {
        $this->subadmins = $subadminRepository;
    }

    public function index(Request $request)
    {
        return view('backend.subadmin.index');
    }

    public function getPending(Request $request)
    {
        return view('backend.subadmin.pending');
    }

    public function getDeactivated(Request $request)
    {
        return view('backend.subadmin.deactivated');
    }

    public function getDeleted(Request $request)
    {
        return view('backend.subadmin.deleted');
    }

    public function show(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        return view('backend.subadmin.show')
            ->withSubadmin($subadmin);
    }

    public function destroy(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->subadmins->delete($subadmin);
        return redirect()->route('admin.subadmin.deleted')->withFlashSuccess('Subadmin Deleted Successfully.');
    }

    public function delete(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->subadmins->forceDelete($subadmin);
        return redirect()->route('admin.subadmin.deleted')->withFlashSuccess('Subadmin Deleted Permanently.');
    }

    public function restore(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        else
        {
            $this->subadmins->restore($subadmin);
            $status = $subadmin->status;
            $approve = $subadmin->approve;

            return redirect()->route($status == 1 ? $approve == 1 ? 'admin.subadmin.index': 'admin.subadmin.pending' : 'admin.subadmin.deactivated')->withFlashSuccess('Subadmin Restored Successfully.');
        }
    }

    public function mark(Subadmin $subadmin, $status, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $approve = $subadmin->approve;
        $this->subadmins->mark($subadmin, $status);
        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.subadmin.index': 'admin.subadmin.pending' : 'admin.subadmin.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    public function approve(Subadmin $subadmin, $approve, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $this->subadmins->approve($subadmin, $approve);
        return redirect()->route($approve == 1 ? 'admin.subadmin.index' : 'admin.subadmin.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

//    public function create(Request $request)
//    {
//        $groupingrules = Groupingrule::where('id', '>', 0)->get();
//        $charitygroups = Charitygroup::where('id', '>', 0)->get();
//        $charities = Charity::where('id', '>', 0)->get();
//        return view('backend.group.create')->with(['groupingrules' => $groupingrules, 'charitygroups' => $charitygroups, 'charities' => $charities]);
//    }
//
//    public function edit($groupId, Request $request)
//    {
//        $group = Group::where('id', $groupId)->first();
//
//        $groupingrules = Groupingrule::where('id', '>', 0)->get();
//        $charitygroups = Charitygroup::where('id', '>', 0)->get();
//        $charities = Charity::where('id', '>', 0)->get();
//
//        $group['advancedvalue'] = [];
//        if($group->rule_type == 1)
//        {
//            $groupingValue = $group->value;
//            $valueItems = explode(",", $groupingValue);
//            $group['advancedvalue'] = $valueItems;
//        }
//        return view('backend.group.edit')->with(['groupingrules' => $groupingrules, 'charitygroups' => $charitygroups, 'charities' => $charities, 'group' => $group]);
//    }



//    public function store(Request $request)
//    {
//        $ruleType = $request->input('ruleType');
//        $groupName = $request->input('name');
//        $selectedRuleId = $request->input('selectrule');
//
//        $selectedcondition = 0;
//        $selectedValue = "";
//        if($ruleType == 2)
//        {
//            $selectedcondition = $request->input('selectedcondition');
//            $selectedValue = $request->input('selectvalue');
//        }
//        else
//        {
//            $selectedValue = $request->get('selectedvalue');
//            $count = 0;
//            if ($request->has('selectedvalue')) {
//                if ($request->get('selectedvalue')) {
//                    foreach($request->get('selectedvalue') as $selectedVal)
//                    {
//                        if($count == 0)
//                        {
//                            $selectedValue = $selectedVal;
//                        }
//                        else
//                        {
//                            $selectedValue .= ",".$selectedVal;
//                        }
//                        $count ++;
//                    }
//                }
//            }
//        }
//
//        $this->groups->create(
//            [
//                'data' => $request->only(
//                    'name',
//                    'selectrule',
//                    'ruleType'
//                ),
//                'selectedcondi' => $selectedcondition,
//                'selectedvalue' => $selectedValue
//            ]);
//        return redirect()->route('admin.group.index')->withFlashSuccess('Group Created Successfully.');
//    }
//
//    public function update(Group $group, Request $request)
//    {
//        $ruleType = $request->input('ruleType');
//        $groupName = $request->input('name');
//        $selectedRuleId = $request->input('selectrule');
//
//        $selectedcondition = 0;
//        $selectedValue = "";
//        if($ruleType == 2)
//        {
//            $selectedcondition = $request->input('selectedcondition');
//            $selectedValue = $request->input('selectvalue');
//        }
//        else
//        {
//            $selectedValue = $request->get('selectedvalue');
//            $count = 0;
//            if ($request->has('selectedvalue')) {
//                if ($request->get('selectedvalue')) {
//                    foreach($request->get('selectedvalue') as $selectedVal)
//                    {
//                        if($count == 0)
//                        {
//                            $selectedValue = $selectedVal;
//                        }
//                        else
//                        {
//                            $selectedValue .= ",".$selectedVal;
//                        }
//                        $count ++;
//                    }
//                }
//            }
//        }
//
//        $this->groups->update($group,
//            [
//                'data' => $request->only(
//                    'name',
//                    'selectrule',
//                    'ruleType'
//                ),
//                'selectedcondi' => $selectedcondition,
//                'selectedvalue' => $selectedValue
//            ]);
//
//        return redirect()->route('admin.group.index')->withFlashSuccess('Group Updated Successfully.');
//    }
//

//
//    public function active(Group $group, Request $request)
//    {
//        $this->groups->activate($group);
//
//        return redirect()->route('admin.group.index')->withFlashSuccess('Group is activated successfully.');
//    }
//
//    /**
//     * @param User              $user
//     * @param ManageUserRequest $request
//     *
//     * @return mixed
//     */
//    public function inactive(Group $group, Request $request)
//    {
//        $this->groups->inactivate($group);
//
//        return redirect()->route('admin.group.index')->withFlashSuccess('Group is inactivated.');
//    }
}
