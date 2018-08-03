<?php

namespace App\Http\Controllers\Backend\Organization;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Group\GroupRepository;
use function foo\func;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class OrganizationTableController extends Controller
{
    protected $groups;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->groups = $groupRepository;
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $status = $request->get('status');
        $approve = $request->get('approved');
        $trashed = $request->get('trashed');

        return Datatables::of($this->groups->getForDataTable($status, $approve, $trashed))
            ->escapeColumns(['name'])
            ->addColumn('full_address', function($groups){
                return $groups->full_address;
            })
            ->addColumn('actions', function ($groups) {
                return $groups->action_buttons;
            })
            ->make(true);
    }
}
