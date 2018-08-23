<?php

namespace App\Http\Controllers\Backend\Alert\Curalert;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Alert\AlertRepository;
use App\Repositories\Backend\Group\GroupRepository;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class CurResponseTableController extends Controller
{
    protected $alerts;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(AlertRepository $alertRepository)
    {
        $this->alerts = $alertRepository;
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $type = $request->input("type");

        return Datatables::of($this->alerts->getForResponseDataTable($type))
            ->escapeColumns(['username', 'locationbtn'])
            ->rawColumns(['locationbtn'])
            ->make(true);
    }
}
