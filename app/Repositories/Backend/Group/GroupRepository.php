<?php

namespace App\Repositories\Backend\Group;

use App\Events\Backend\Group\GroupDeleted;
use App\Models\Access\User\User;
use App\Models\Group;
use App\Notifications\Backend\Organization\OrganizationApproveChanged;
use App\Notifications\Backend\Organization\OrganizationDeleteChanged;
use App\Notifications\Backend\Organization\OrganizationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRepository.
 */
class GroupRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Group::class;

    public function __construct()
    {

    }

    public function create(array $input)
    {
        $data = $input['data'];
        $organizationName = $input['title'];
        $logoUrl = $input['logoimg'];

        $this->checkGroupNameIfExist($organizationName);

        $group = self::MODEL;
        $group = new $group;
        $group->name = $organizationName;
        $group->address1 = $data['address1'];
        $group->address2 = $data['address2'];
        $group->city = $data['city'];
        $group->zip = $data['zip'];
        $group->state = $data['state'];
        $group->country = $data['country'];
        $group->logo = $logoUrl;

        if(access()->hasRole(1))
        {
            $group->approved = 1;
            $group->groupid = $this->generateRndString(5);
        }

        DB::transaction(function () use ($group) {
            if ($group->save()) {
                if(access()->hasRole(2))
                {
                    $user = access()->user();
                    $user->organization = $group->id;

                    if($this->checkGroupInitialAdmin($group->id))
                    {
                        $user->isinitial = 1;
                    }
                    $user->save();
                }
            }
        });
        return $group;
    }

    public function update(Model $group, array $input)
    {
        $data = $input['data'];
        $logoUrl = $input['logoimg'];

        $group->address1 = $data['address1'];
        $group->address2 = $data['address2'];
        $group->city = $data['city'];
        $group->zip = $data['zip'];
        $group->state = $data['state'];
        $group->country = $data['country'];

        if($logoUrl != "" && $logoUrl != null)
        {
            $group->logo = $logoUrl;
        }

        DB::transaction(function () use ($group) {
            if ($group->save()) {
            }
        });
        return $group;
    }

    protected function checkGroupNameIfAvailable($group, $input)
    {
        if ($group->name != $input['name'])
        {
            if ($this->query()->where('name', '=', $input['name'])->first())
            {
                throw new GeneralException('Existing Organization Name used.');
            }
        }
    }

    protected function checkGroupNameIfExist($input)
    {
        if($this->query()->where('name', $input)->first())
        {
            throw new GeneralException('Existing Organization Name Used.');
        }
    }

    protected function checkGroupInitialAdmin($groupId)
    {
        $initialAdmin = User::where('organization', $groupId)->where('isinitial', 1)->first();
        if($initialAdmin == null)
        {
            return true;
        }
        return false;
    }

    public function delete(Group $group)
    {
        if ($group->delete()) {
            event(new GroupDeleted($group));
            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
            foreach ($admins as $admin)
            {
                $admin->notify(new OrganizationDeleteChanged(0, $group));
            }
            return true;
        }
        throw new GeneralException('Error in removing organization.');
    }

    public function forceDelete(Group $organization)
    {
        if (is_null($organization->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($organization) {
            if ($organization->forceDelete()) {
                $admins = User::where('organization', $organization->id)->where('isadmin', 1)->get();
                foreach ($admins as $admin)
                {
                    $admin->notify(new OrganizationDeleteChanged(2, $organization));
                }
                return true;
            }

            throw new GeneralException('Error in organization permanently deleting.');
        });
    }

    public function restore(Group $organization)
    {
        if (is_null($organization->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($organization->restore()) {

            $admins = User::where('organization', $organization->id)->where('isadmin', 1)->get();
            foreach ($admins as $admin)
            {
                $admin->notify(new OrganizationDeleteChanged(1, $organization));
            }
            return true;
        }

        throw new GeneralException('Error in organization restoring.');
    }

    public function mark(Group $group, $status)
    {
        $group->status = $status;

        if ($group->save()) {
            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
            foreach ($admins as $admin)
            {
                $admin->notify(new OrganizationStatusChanged($status, $group));
            }
            return true;
        }

        throw new GeneralException('Organization Updating Error');
    }

    public function approve(Group $group, $approve)
    {
        $group->approved = $approve;

        if($approve == 1)
        {
            if($group->groupid == "" || $group->groupid == null)
            {
                $group->groupid = $this->generateRndString(5);
            }
        }

        if ($group->save()) {
            $admins = User::where('organization', $group->id)->where('isadmin', 1)->get();
            foreach ($admins as $admin)
            {
                $admin->notify(new OrganizationApproveChanged($approve, $group));
            }
            return true;
        }

        throw new GeneralException('Error in approval.');
    }

    public function getForDataTable($status = 1, $approve = 1, $trashed = false)
    {
        if ($trashed == 'true') {

            $groups = $this->query();
            $groups = $groups->onlyTrashed();

            return $groups;
        }
        else
        {
            if($status == 0)
            {
                $groups = $this->query()->where('status', $status)->orderby('created_at', 'ASC')->get();
            }
            else
            {
                $groups = $this->query()->where('status', $status)->where('approved', $approve)->orderby('created_at', 'ASC')->get();
            }
            return $groups;
        }
    }

    function generateRndString($length = 5) {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
