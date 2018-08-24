<?php

namespace App\Repositories\Backend\Access\User;

use App\Models\Access\User\User;
use App\Models\Group;
use App\Notifications\Backend\Access\AccountCreated;
use App\Notifications\Backend\Access\UserApproveChanged;
use App\Notifications\Backend\Access\UserDeleteChanged;
use App\Notifications\Backend\Access\UserStatusChanged;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\Backend\Access\User\UserCreated;
use App\Events\Backend\Access\User\UserDeleted;
use App\Events\Backend\Access\User\UserUpdated;
use App\Events\Backend\Access\User\UserRestored;
use App\Events\Backend\Access\User\UserConfirmed;
use App\Events\Backend\Access\User\UserDeactivated;
use App\Events\Backend\Access\User\UserReactivated;
use App\Events\Backend\Access\User\UserUnconfirmed;
use App\Events\Backend\Access\User\UserPasswordChanged;
use App\Notifications\Backend\Access\UserAccountActive;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Events\Backend\Access\User\UserPermanentlyDeleted;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = User::class;

    /**
     * @var RoleRepository
     */
    protected $role;

    /**
     * @param RoleRepository $role
     */
    public function __construct(RoleRepository $role)
    {
        $this->role = $role;
    }

    /**
     * @param        $permissions
     * @param string $by
     *
     * @return mixed
     */
    public function getByPermission($permissions, $by = 'name')
    {
        if (! is_array($permissions)) {
            $permissions = [$permissions];
        }

        return $this->query()->whereHas('roles.permissions', function ($query) use ($permissions, $by) {
            $query->whereIn('permissions.'.$by, $permissions);
        })->get();
    }

    /**
     * @param        $roles
     * @param string $by
     *
     * @return mixed
     */
    public function getByRole($roles, $by = 'name')
    {
        if (! is_array($roles)) {
            $roles = [$roles];
        }

        return $this->query()->whereHas('roles', function ($query) use ($roles, $by) {
            $query->whereIn('roles.'.$by, $roles);
        })->get();
    }

    /**
     * @param int  $status
     * @param bool $trashed
     *
     * @return mixed
     */
    public function getForDataTable($admin = 1, $status = 1, $approve = 1, $trashed = false)
    {
        /**
         * Note: You must return deleted_at or the User getActionButtonsAttribute won't
         * be able to differentiate what buttons to show for each row.
         */
        $dataTableQuery = $this->query()
            ->with('roles')
            ->select([
                config('access.users_table').'.id',
                config('access.users_table').'.first_name',
                config('access.users_table').'.last_name',
                config('access.users_table').'.profile_picture',
                config('access.users_table').'.email',
                config('access.users_table').'.status',
                config('access.users_table').'.organization',
                config('access.users_table').'.isadmin',
                config('access.users_table').'.isinitial',
                config('access.users_table').'.approve',
                config('access.users_table').'.confirmed',
                config('access.users_table').'.created_at',
                config('access.users_table').'.updated_at',
                config('access.users_table').'.deleted_at',
            ]);

        if ($trashed == 'true') {
            $dataTableQuery = $dataTableQuery->onlyTrashed()->admin($admin);
            return $dataTableQuery;
        }

        // active() is a scope on the UserScope trait
        if($status != 2)
        {
           $dataTableQuery = $dataTableQuery->active($status);
        }

        if($approve != 2)
        {
            $dataTableQuery = $dataTableQuery->approved($approve);
        }
        return $dataTableQuery->admin($admin);
    }

    /**
     * @return mixed
     */
    public function getUnconfirmedCount()
    {
        return $this->query()->where('confirmed', 0)->count();
    }

    public function createUserFromSuperAdmin($input)
    {
        $data = $input['data'];
        $profile = $input['profileimg'];
        $type = $input['type'];

        $exist = $this->query()->where('email', $data['email'])->first();

        if($exist != null)
        {
            throw new GeneralException(trans('Duplicated email address!'));
        }

        $user = self::MODEL;
        $user = new $user;
        $user->first_name = $data['firstname'];
        $user->last_name = $data['lastname'];
        $user->email = $data['email'];
        $user->phonenumber = $data['phonenumber'];
        $user->password = bcrypt('123456');
        $user->status = 1;
        $user->isadmin = $type;
        $user->organization = $data['organization'];
        $user->isinitial = 0;
        $user->approve = 1;
        $user->profile_picture = $profile;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = 1;

        DB::transaction(function () use ($user, $data, $type) {
            if ($user->save()) {
                if($type == 0)
                {
                    $user->attachRoles(3);
                }
                else
                {
                    $user->attachRoles(2);
                }

                $org = $user->organization;
                $orgInfo = Group::where('id', $org)->first();
                $groupName = "";

                if($orgInfo != null)
                {
                    $groupName = $orgInfo->name;
                }

                $user->notify(new AccountCreated($user->isadmin, $groupName));

                return true;
            }

            if($type == 0)
            {
                throw new GeneralException(trans('Creating user error!'));
            }
            else
            {
                throw new GeneralException(trans('Creating admin error!'));
            }
        });
    }
    /**
     * @param array $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $roles = $input['roles'];

        $user = $this->createUserStub($data);

        DB::transaction(function () use ($user, $data, $roles) {
            if ($user->save()) {
                //User Created, Validate Roles
                /*if (! count($roles['assignees_roles'])) {
                    throw new GeneralException(trans('exceptions.backend.access.users.role_needed_create'));
                }
*/
                //Attach new roles
                $user->attachRoles(2);

                //Send confirmation email if requested and account approval is off
                if (isset($data['confirmation_email']) && $user->confirmed == 0 && ! config('access.users.requires_approval')) {
                    $user->notify(new UserNeedsConfirmation($user->confirmation_code));
                }

                event(new UserCreated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.create_error'));
        });
    }

    public function createUser($input)
    {
        $curAdmin = access()->user();
        $data = $input['data'];
        $profile = $input['profileimg'];

        $exist = $this->query()->where('email', $data['email'])->first();

        if($exist != null)
        {
            throw new GeneralException(trans('Duplicated email address!'));
        }

        $user = self::MODEL;
        $user = new $user;
        $user->first_name = $data['firstname'];
        $user->last_name = $data['lastname'];
        $user->email = $data['email'];
        $user->phonenumber = $data['phonenumber'];
        $user->password = bcrypt('123456');
        $user->status = 1;
        $user->isadmin = 0;
        $user->organization = $curAdmin->organization;
        $user->isinitial = 0;
        $user->approve = 1;
        $user->profile_picture = $profile;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = 1;

        DB::transaction(function () use ($user, $data) {
            if ($user->save()) {
                $user->attachRoles(3);
                event(new UserCreated($user));

                $org = $user->organization;
                $orgInfo = Group::where('id', $org)->first();
                $groupName = "";

                if($orgInfo != null)
                {
                    $groupName = $orgInfo->name;
                }

                $user->notify(new AccountCreated($user->isadmin, $groupName));

                return true;
            }
            throw new GeneralException(trans('Creating user error!'));
        });
    }

    /**
     * @param Model $user
     * @param array $input
     *
     * @return bool
     * @throws GeneralException
     */
    public function update(Model $user, array $input)
    {
        $data = $input['data'];
        $isinitial = $input['isinitial'];
        $url = $input['url'];
        //$this->checkUserByEmail($data, $user);

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->phonenumber = $data['phonenumber'];
        $user->organization = $data['organization'];

        if($isinitial != 2)
        {
            $user->isinitial = $isinitial;

            if($isinitial == 1)
            {
                $users = User::where('organization', $user->organization)->where('isadmin', 1)->update(['isinitial' => 0]);
            }
        }

        if($url != "")
        {
            $user->profile_picture = $url;
        }

        DB::transaction(function () use ($user, $data) {
            if ($user->save()) {
                event(new UserUpdated($user));

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.update_error'));
        });
    }

    /**
     * @param Model $user
     * @param $input
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function updatePassword(Model $user, $input)
    {
        $user->password = bcrypt($input['password']);

        if ($user->save()) {
            event(new UserPasswordChanged($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function delete(Model $user)
    {
        if (access()->id() == $user->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        if ($user->id == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_admin'));
        }

        if ($user->delete()) {
            event(new UserDeleted($user));
            $user->notify(new UserDeleteChanged(0));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     */
    public function forceDelete(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($user) {
            if ($user->forceDelete()) {
                event(new UserPermanentlyDeleted($user));
                $user->notify(new UserDeleteChanged(2));
                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
        });
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore(Model $user)
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($user->restore()) {
            event(new UserRestored($user));
            $user->notify(new UserDeleteChanged(1));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param Model $user
     * @param $status
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function mark(Model $user, $status)
    {
        if (access()->id() == $user->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user->status = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if ($user->save()) {
            $user->notify(new UserStatusChanged($status));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param Model $user
     *
     * @return bool
     * @throws GeneralException
     */
    public function confirm(Model $user)
    {
        if ($user->confirmed == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.already_confirmed'));
        }

        $user->confirmed = 1;
        $confirmed = $user->save();

        if ($confirmed) {
            event(new UserConfirmed($user));

            // Let user know their account was approved
            if (config('access.users.requires_approval')) {
                $user->notify(new UserAccountActive());
            }

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.cant_confirm'));
    }

    /**
     * @param Model $user
     *
     * @return bool
     * @throws GeneralException
     */
    public function unconfirm(Model $user)
    {
        if ($user->confirmed == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.not_confirmed'));
        }

        if ($user->id == 1) {
            // Cant un-confirm admin
            throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm_admin'));
        }

        if ($user->id == access()->id()) {
            // Cant un-confirm self
            throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm_self'));
        }

        $user->confirmed = 0;
        $unconfirmed = $user->save();

        if ($unconfirmed) {
            event(new UserUnconfirmed($user));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.cant_unconfirm')); // TODO
    }

    /**
     * @param  $input
     * @param  $user
     *
     * @throws GeneralException
     */
    protected function checkUserByEmail($input, $user)
    {
        //Figure out if email is not the same
        if ($user->email != $input['email']) {
            //Check to see if email exists
            if ($this->query()->where('email', '=', $input['email'])->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }

    /**
     * @param $roles
     * @param $user
     */
    protected function flushRoles($roles, $user)
    {
        //Flush roles out, then add array of new ones
        $user->detachRoles($user->roles);
        $user->attachRoles($roles['assignees_roles']);
    }

    /**
     * @param  $roles
     *
     * @throws GeneralException
     */
    protected function checkUserRolesCount($roles)
    {
        //User Updated, Update Roles
        //Validate that there's at least one role chosen
        if (count($roles['assignees_roles']) == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.role_needed'));
        }
    }

    /**
     * @param  $input
     *
     * @return mixed
     */
    protected function createUserStub($input)
    {
        $user = self::MODEL;
        $user = new $user;
        $user->real_name = $input['real_name'];
        $user->hero_name = $input['hero_name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->age = $input['age'];
        $user->sex = $input['sex'];
        $user->card_num = $input['cardnum'];
        $user->status = isset($input['status']) ? 1 : 0;
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->confirmed = isset($input['confirmed']) ? 1 : 0;

        return $user;
    }


    public function getForMyUserTable($status = 1, $approve = 1, $trashed = false)
    {
        $curUser = access()->user();
        $organizationId = $curUser->organization;

        if ($trashed == 'true') {

            $myusers = $this->query();
            $myusers = $myusers->onlyTrashed();
            $myusers = $myusers->where('id', '<>', $curUser->id)
                ->where('id', '>', 1)
                ->where('isadmin', 0)
                ->where('organization', $organizationId);
            return $myusers;
        }
        else
        {
            if($status == 0)
            {
                $myusers = $this->query()->where('organization', $organizationId)
                    ->where('id', '<>', $curUser->id)
                    ->where('id', '>', 1)
                    ->where('status', $status)
                    ->where('isadmin', 0)
                    ->orderby('created_at', 'ASC')->get();
            }
            else
            {
                $myusers = $this->query()->where('organization', $organizationId)
                    ->where('id', '<>', $curUser->id)
                    ->where('id', '>', 1)
                    ->where('status', $status)
                    ->where('approve', $approve)
                    ->where('isadmin', 0)
                    ->orderby('created_at', 'ASC')->get();
            }
            return $myusers;
        }
    }

    public function CheckIfMyUser(User $myuser)
    {
        $me = access()->user();
        $myOrganization = $me->organization;

        $myUserRole = $myuser->roles->first()->id;

        if($myUserRole != 3)
        {
            return false;
        }

        if($myuser->organization != $myOrganization)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function approve(User $user, $approve)
    {
        if (access()->id() == $user->id && $approve == 0) {
            throw new GeneralException('You can not approve your self.');
        }

        $user->approve = $approve;

        if ($user->save()) {
            $user->notify(new UserApproveChanged($user->isadmin, $approve));
            return true;
        }

        throw new GeneralException('Error in approval.');
    }
}
