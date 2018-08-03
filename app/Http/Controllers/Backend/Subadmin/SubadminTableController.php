<?php

namespace App\Http\Controllers\Backend\Subadmin;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Subadmin\SubadminRepository;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;
use Illuminate\Http\Request;
/**
 * Class RoleTableController.
 */
class SubadminTableController extends Controller
{
    protected $subadmins;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(SubadminRepository $subadminRepository)
    {
        $this->subadmins = $subadminRepository;
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

        return Datatables::of($this->subadmins->getForDataTable($status, $approve, $trashed))
            ->escapeColumns(['name'])
            ->addColumn('actions', function ($subadmins) {
                return $subadmins->action_buttons;
            })
            ->make(true);
    }
}
