<?php

namespace App\Repositories\Backend\Subadmin;


use App\Models\Subadmin\Subadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Access\User\User;
/**
 * Class UserRepository.
 */
class SubadminRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Subadmin::class;

    public function __construct()
    {

    }

    public function getForDataTable($status = 1, $approve = 1, $trashed = false)
    {
        $curUser = access()->user();
        $organizationId = $curUser->organization;

        if ($trashed == 'true') {

            $subadmins = $this->query();
            $subadmins = $subadmins->onlyTrashed();
            $subadmins = $subadmins->where('id', '<>', $curUser->id)
                                ->where('id', '>', 1)
                                ->where('isadmin', 1)
                                ->where('organization', $organizationId);
            return $subadmins;
        }
        else
        {
            if($status == 0)
            {
                $subadmins = $this->query()->where('organization', $organizationId)
                    ->where('id', '<>', $curUser->id)
                    ->where('id', '>', 1)
                    ->where('status', $status)
                    ->where('isadmin', 1)
                    ->orderby('created_at', 'ASC')->get();
            }
            else
            {
                $subadmins = $this->query()->where('organization', $organizationId)
                    ->where('id', '<>', $curUser->id)
                    ->where('id', '>', 1)
                    ->where('status', $status)
                    ->where('approve', $approve)
                    ->where('isadmin', 1)
                    ->orderby('created_at', 'ASC')->get();
            }
            return $subadmins;
        }
    }

    public function CheckIfMySubadmin(Subadmin $subadmin)
    {
        $me = access()->user();

        if($me->id == $subadmin->id)
        {
            return false;
        }

        $myOrganization = $me->organization;

        $subadminRole = $subadmin->roles->first()->id;

        if($subadminRole != 2)
        {
            return false;
        }

        if($subadmin->organization != $myOrganization)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function delete(Subadmin $subadmin)
    {
        if (access()->id() == $subadmin->id) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_self'));
        }

        if ($subadmin->id == 1) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_delete_admin'));
        }

        if ($subadmin->delete()) {
            return true;
        }

        throw new GeneralException("Error occurs during delete sub admin.");
    }

    public function forceDelete(Subadmin $subadmin)
    {
        if (is_null($subadmin->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.delete_first'));
        }

        DB::transaction(function () use ($subadmin) {
            if ($subadmin->forceDelete()) {

                return true;
            }

            throw new GeneralException(trans('exceptions.backend.access.users.delete_error'));
        });
    }

    public function restore(Subadmin $subadmin)
    {
        if (is_null($subadmin->deleted_at)) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_restore'));
        }

        if ($subadmin->restore()) {

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.restore_error'));
    }

    public function mark(Subadmin $subadmin, $status)
    {
        if (access()->id() == $subadmin->id && $status == 0) {
            throw new GeneralException(trans('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $subadmin->status = $status;

        if ($subadmin->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.mark_error'));
    }

    public function approve(Subadmin $subadmin, $approve)
    {
        if (access()->id() == $subadmin->id && $approve == 0) {
            throw new GeneralException('You can not approve your self.');
        }

        $subadmin->approve = $approve;

        if ($subadmin->save()) {
            return true;
        }

        throw new GeneralException('Error in approval.');
    }
}
