<?php

namespace App\Http\Controllers\Backend\Myuser;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;

class MyuserController extends Controller
{
    protected $myusers;

    public function __construct(UserRepository $userRepository)
    {
        $this->myusers = $userRepository;
    }

    public function index(Request $request)
    {
        return view('backend.myuser.index');
    }

    public function getPending(Request $request)
    {
        return view('backend.myuser.pending');
    }

    public function getDeactivated(Request $request)
    {
        return view('backend.myuser.deactivated');
    }

    public function getDeleted(Request $request)
    {
        return view('backend.myuser.deleted');
    }

    public function show(User $user, Request $request)
    {
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        return view('backend.myuser.show')
            ->withMyuser($user);
    }

    public function approve(User $user, $approve, Request $request)
    {
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $this->myusers->approve($user, $approve);
        return redirect()->route($approve == 1 ? 'admin.myuser.index' : 'admin.myuser.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    public function mark(User $user, $status, Request $request)
    {
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $approve = $user->approve;
        $this->myusers->mark($user, $status);
        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.myuser.index': 'admin.myuser.pending' : 'admin.myuser.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    public function destroy(User $user, Request $request)
    {
        //$user = User::where('id', $userId)->first();
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->myusers->delete($user);
        return redirect()->route('admin.myuser.deleted')->withFlashSuccess('User Deleted Successfully.');
    }

    public function delete(User $user, Request $request)
    {
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->myusers->forceDelete($user);
        return redirect()->route('admin.myuser.deleted')->withFlashSuccess('User Deleted Permanently.');
    }

    public function restore(User $user, Request $request)
    {
        if(!$this->myusers->CheckIfMyUser($user))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        else
        {
            $this->myusers->restore($user);
            $status = $user->status;
            $approve = $user->approve;

            return redirect()->route($status == 1 ? $approve == 1 ? 'admin.myuser.index': 'admin.myuser.pending' : 'admin.myuser.deactivated')->withFlashSuccess('User Restored Successfully.');
        }
    }
}
