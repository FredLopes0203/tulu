<?php

namespace App\Http\Controllers\Backend\Alert\Curalert;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Alert\AlertRepository;
use App\Repositories\Backend\Group\GroupRepository;
use Illuminate\Support\Facades\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class CuralertTableController extends Controller
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
        return Datatables::of($this->alerts->getForDataTable())
            ->escapeColumns(['title'])
            ->addColumn('creator_name', function($alert){
                return $alert->creator_name;
            })
            ->addColumn('type_label', function($alert){
                return $alert->type_label;
            })
            ->make(true);
    }
}
