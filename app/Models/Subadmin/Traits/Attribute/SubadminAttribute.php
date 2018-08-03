<?php

namespace App\Models\Subadmin\Traits\Attribute;

/**
 * Class UserAttribute.
 */
trait SubadminAttribute
{
    public function isActive()
    {
        return $this->status == 1;
    }

    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }

    public function isApproved()
    {
        return $this->approve == 1;
    }

    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.subadmin.show', $this).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.view').'"></i></a> ';
    }

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.subadmin.edit', $this).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.edit').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getChangePasswordButtonAttribute()
    {
        return '<a href="'.route('admin.subadmin.change-password', $this).'" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.change_password').'"></i></a> ';
    }

    /**
     * @return string
     */

    public function getPictureAttribute()
    {
        return $this->getPicture();
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPicture($size = false)
    {
        if($this->profile_picture != "")
        {
            return asset($this->profile_picture);
        }

        return asset("img/profile/sample.png");
    }

    public function getStatusButtonAttribute()
    {
        if ($this->id != access()->id()) {
            switch ($this->status) {
                case 0:
                    return '<a href="'.route('admin.subadmin.mark', [
                        $this,
                        1,
                    ]).'" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.activate').'"></i></a> ';
                // No break

                case 1:
                    return '<a href="'.route('admin.subadmin.mark', [
                        $this,
                        0,
                    ]).'" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.deactivate').'"></i></a> ';
                // No break

                default:
                    return '';
                // No break
            }
        }

        return '';
    }

    public function getApproveButtonAttribute()
    {
        if ($this->id != access()->id()) {
            switch ($this->approve) {
                case 0:
                    return '<a href="'.route('admin.subadmin.approve', [
                            $this,
                            1,
                        ]).'" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Approve"></i></a> ';
                // No break

                case 1:
                    return '<a href="'.route('admin.subadmin.approve', [
                            $this,
                            0,
                        ]).'" class="btn btn-xs btn-warning"><i class="fa fa-close" data-toggle="tooltip" data-placement="top" title="Reject"></i></a> ';
                // No break

                default:
                    return '';
                // No break
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getConfirmedButtonAttribute()
    {
        if (! $this->isConfirmed() && ! config('access.users.requires_approval')) {
            return '<a href="'.route('admin.access.user.account.confirm.resend', $this).'" class="btn btn-xs btn-success"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title='.trans('buttons.backend.access.users.resend_email').'"></i></a> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if ($this->id != access()->id() && $this->id != 1) {
            return '<a href="'.route('admin.subadmin.destroy', $this).'"
                 data-method="delete"
                 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getRestoreButtonAttribute()
    {
        return '<a href="'.route('admin.subadmin.restore', $this).'" name="restore_user" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="Restore Subadmin"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeletePermanentlyButtonAttribute()
    {
        return '<a href="'.route('admin.subadmin.delete-permanently', $this).'" name="delete_user_perm" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.delete_permanently').'"></i></a> ';
    }

    /**
     * @return string
     */

    public function getStatusLabelAttribute()
    {
        if ($this->isActive()) {
            return "<label class='label label-success'>".trans('labels.general.active').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.inactive').'</label>';
    }

    public function getApproveLabelAttribute()
    {
        if ($this->isApproved()) {
            return "<label class='label label-success'>Approved</label>";
        }

        return "<label class='label label-danger'>Pending</label>";
    }

    public function getActionButtonsAttribute()
    {
        if ($this->trashed()) {
            return $this->restore_button.$this->delete_permanently_button;
        }

        if($this->status == 0)
        {
            return
                $this->show_button.
                $this->status_button.
                $this->delete_button;
        }
        else
        {
            return
                $this->show_button.
                $this->approve_button.
                $this->status_button.
                $this->delete_button;
        }
    }
}
