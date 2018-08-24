<?php

namespace App\Http\Controllers\Backend\Myuser;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Access\User\User;
use App\Models\Group;
use App\Notifications\Backend\Access\AccountCreated;
use App\Repositories\Backend\Access\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

    public function create(Request $request)
    {
        return view('backend.myuser.create');
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

    public function store(Request $request)
    {
        $useremail = $request->input('email');

        $url = "";
        $imageData = $request->input('imgData');

        if($imageData != null && $imageData != "")
        {
            $num = $this->generateRndString();
            $email = str_replace(' ', '', $useremail);
            $fileName = $email . '_'.$num.'png';
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            File::put(base_path() . '/public/img/profile/'.$fileName, base64_decode($imageData));
            $url = 'img/profile/' . $fileName;
        }

        $this->myusers->createUser(
            [
                'data' => $request->only(
                    'firstname',
                    'lastname',
                    'email',
                    'phonenumber'
                ),
                'profileimg' => $url
            ]
        );

        return redirect()->route('admin.myuser.index')->withFlashSuccess('User Created Successfully.');
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

    function generateRndString($length = 6) {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
