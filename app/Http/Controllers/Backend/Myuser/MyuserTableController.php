<?php

namespace App\Http\Controllers\Backend\Myuser;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Http\Requests\Backend\Access\Role\ManageRoleRequest;

/**
 * Class RoleTableController.
 */
class MyuserTableController extends Controller
{
    protected $users;

    /**
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
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

        return Datatables::of($this->users->getForMyUserTable($status, $approve, $trashed))
            ->escapeColumns(['name'])
            ->addColumn('actions', function ($users) {
                return $users->action_buttons;
            })
            ->make(true);
    }
}
