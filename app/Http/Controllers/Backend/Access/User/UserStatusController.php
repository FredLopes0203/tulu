<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;

/**
 * Class UserStatusController.
 */
class UserStatusController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }


    public function getPending(ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        if($route == "admin.access.user.pending")
        {
            return view('backend.access.pending')->withType(0);
        }
        else
        {
            return view('backend.access.pending')->withType(1);
        }
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function getDeactivated(ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        if($route == "admin.access.user.deactivated")
        {
            return view('backend.access.deactivated')->withType(0);
        }
        else
        {
            return view('backend.access.deactivated')->withType(1);
        }
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function getDeleted(ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        if($route == "admin.access.user.deleted")
        {
            return view('backend.access.deleted')->withType(0);
        }
        else
        {
            return view('backend.access.deleted')->withType(1);
        }
    }

    /**
     * @param User $user
     * @param $status
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function mark(User $user, $status, ManageUserRequest $request)
    {
        $this->users->mark($user, $status);

        $approve = $user->approve;
        if($user->isadmin == 1)
        {
            return redirect()->route($status == 1 ? $approve == 1 ? 'admin.access.manager.index' : 'admin.access.manager.pending' :'admin.access.manager.deactivated')->withFlashSuccess('The admin updated successfully.');
        }

        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.access.user.index' : 'admin.access.user.pending' : 'admin.access.user.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    public function approve(User $user, $approve, ManageUserRequest $request)
    {
        $this->users->approve($user, $approve);

        if($user->isadmin == 1)
        {
            return redirect()->route($approve == 1 ? 'admin.access.manager.index' : 'admin.access.manager.pending')->withFlashSuccess('The admin updated successfully.');
        }

        return redirect()->route($approve == 1 ? 'admin.access.user.index' : 'admin.access.user.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    /**
     * @param User              $deletedUser
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function delete(User $deletedUser, ManageUserRequest $request)
    {
        $this->users->forceDelete($deletedUser);

        if($deletedUser->isadmin == 1)
        {
            return redirect()->route('admin.access.manager.deleted')->withFlashSuccess('Admin was deleted permanently.');
        }
        return redirect()->route('admin.access.user.deleted')->withFlashSuccess(trans('alerts.backend.users.deleted_permanently'));
    }

    /**
     * @param User              $deletedUser
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function restore(User $deletedUser, ManageUserRequest $request)
    {
        $this->users->restore($deletedUser);

        $status = $deletedUser->status;
        $approve = $deletedUser->approve;

        if($deletedUser->isadmin == 1)
        {
            return redirect()->route($status == 1 ? $approve == 1 ? 'admin.access.manager.index' : 'admin.access.manager.pending': 'admin.access.manager.deactivated')->withFlashSuccess('Admin restored successfully.');
        }
        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.access.user.index' : 'admin.access.user.pending': 'admin.access.user.deactivated')->withFlashSuccess('User restored successfully.');
    }
}
