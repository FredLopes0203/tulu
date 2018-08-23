<?php

namespace App\Http\Controllers\Backend\Alert\History;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Alert\AlertRepository;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class AlertdetailTableController extends Controller
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
        $alertID = $request->get('alertid');
        $diff = $request->get('diff');

        return Datatables::of($this->alerts->getForHistoryDetailDataTable($alertID, $diff))
            ->escapeColumns(['title'])
            ->addColumn('creator_name', function($alert){
                return $alert->creator_name;
            })
            ->addColumn('type_label', function($alert){
                return $alert->type_label;
            })
            ->addColumn('createdtime', function($alert){
                return '<b style="font-size: 16px;">'.$alert->createddate.'</b><br><b>'.$alert->createdhour.'</b>';
            })
            ->make(true);
    }
}
