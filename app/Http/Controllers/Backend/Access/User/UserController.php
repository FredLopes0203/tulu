<?php

namespace App\Http\Controllers\Backend\Access\User;

use App\Exceptions\GeneralException;
use App\Http\Requests\Request;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Notifications\Backend\Access\AccountCreated;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Http\Requests\Backend\Access\User\StoreUserRequest;
use App\Http\Requests\Backend\Access\User\ManageUserRequest;
use App\Http\Requests\Backend\Access\User\UpdateUserRequest;
use Illuminate\Support\Facades\File;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var RoleRepository
     */
    protected $roles;

    /**
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        if($route == "admin.access.user.index")
        {
            return view('backend.access.index')->withType(0);
        }
        else
        {
            return view('backend.access.index')->withType(1);
        }
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request)
    {
        $route = \Route::current()->getName();
        $organizations = Group::where('approved', 1)->where('status', 1)->get();

        if($route == "admin.access.user.create")
        {
            return view('backend.access.create')->withType(0)->withOrganizations($organizations);
        }
        else
        {
            return view('backend.access.create')->withType(1)->withOrganizations($organizations);
        }
    }

    /**
     * @param StoreUserRequest $request
     *
     * @return mixed
     */
    public function store(ManageUserRequest $request)
    {
        $type = 0;
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

        $route = \Route::current()->getName();

        if($route == "admin.access.user.store")
        {
            $type = 0;
        }
        else
        {
            $type = 1;
        }

        $this->users->createUserFromSuperAdmin(
            [
                'data' => $request->only(
                    'firstname',
                    'lastname',
                    'email',
                    'phonenumber',
                    'organization'
                ),
                'type' => $type,
                'profileimg' => $url
            ]);

        if($type == 0)
        {
            return redirect()->route('admin.access.user.index')->withFlashSuccess('User Created Successfully!');
        }
        else
        {
            return redirect()->route('admin.access.manager.index')->withFlashSuccess('Admin Created Successfully!');
        }
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function show(User $user, ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        $type = $user->isadmin;
        if($user->id == 1)
        {
            throw new GeneralException('Invalid User.');
        }

        if($route == "admin.access.user.show")
        {
            if($type == 1)
            {
                throw new GeneralException('Invalid User.');
            }
        }
        else
        {
            if($type == 0)
            {
                throw new GeneralException('Invalid Admin.');
            }
        }

        return view('backend.access.show')
            ->withUser($user)->withType($type);
    }

    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function edit(User $user, ManageUserRequest $request)
    {
        $route = \Route::current()->getName();

        $type = $user->isadmin;
        if($user->id == 1)
        {
            throw new GeneralException('Invalid User.');
        }

        if($route == "admin.access.user.edit")
        {
            if($type == 1)
            {
                throw new GeneralException('Invalid User.');
            }
        }
        else
        {
            if($type == 0)
            {
                throw new GeneralException('Invalid Admin.');
            }
        }

        $organizations = Group::where('approved', 1)->where('status', 1)->get();

        return view('backend.access.edit')
            ->withUser($user)
            ->withType($type)
            ->withOrganizations($organizations)
            ->withUserRoles($user->roles->pluck('id')->all())
            ->withRoles($this->roles->getAll());
    }

    /**
     * @param User              $user
     * @param UpdateUserRequest $request
     *
     * @return mixed
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $isinitial = 2;
        if($user->isadmin == 1)
        {
            $isinitial = $request->input('initialadmin');
            if($isinitial == null)
            {
                $isinitial = 0;
            }
        }

        $url = "";
        $imageData = $request->input('imgData');

        if($imageData != null && $imageData != "")
        {
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);

            $num = $this->generateRndString();
            $fileName = access()->user()->email . '_'.$num.'.png';
            File::put(base_path() . '/public/img/profile/'.$fileName, base64_decode($imageData));
            $url = 'img/profile/' . $fileName;
        }

        $this->users->update($user,
            [
                'data' => $request->only(
                    'first_name',
                    'last_name',
                    'phonenumber',
                    'organization'
                ),
                'isinitial' => $isinitial,
                'url' => $url
            ]);


        if($user->isadmin == 1)
        {
            if($user->status == 0)
            {
                return redirect()->route('admin.access.manager.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
            }
            else
            {
                if($user->approve == 1)
                {
                    return redirect()->route('admin.access.manager.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
                }
                else
                {
                    return redirect()->route('admin.access.manager.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
                }
            }
        }
        else
        {
            if($user->status == 0)
            {
                return redirect()->route('admin.access.user.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
            }
            else
            {
                if($user->approve == 1)
                {
                    return redirect()->route('admin.access.user.index')->withFlashSuccess(trans('alerts.backend.users.updated'));
                }
                else
                {
                    return redirect()->route('admin.access.user.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
                }
            }
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
    /**
     * @param User              $user
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function destroy(User $user, ManageUserRequest $request)
    {
        $this->users->delete($user);
        if($user->isadmin == 1)
        {
            return redirect()->route('admin.access.manager.deleted')->withFlashSuccess('Admin deleted successfully.');
        }

        return redirect()->route('admin.access.user.deleted')->withFlashSuccess(trans('alerts.backend.users.deleted'));
    }

    public function updateProfilePicture(Request $request)
    {
        //$selectedUser = $request->input('selectedUserID');
        return response()->json(["result"=>true]);
    }
}
