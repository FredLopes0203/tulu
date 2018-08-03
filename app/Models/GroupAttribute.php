<?php

namespace App\Models;

/**
 * Class RoleAttribute.
 */
trait GroupAttribute
{

    public function isActive()
    {
        return $this->status == 1;
    }

    public function isApproved()
    {
        return $this->approved == 1;
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        $returnvalue = '<a href="'.route('admin.organization.edit', $this).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.edit').'"></i></a> ';
        return $returnvalue;
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        $returnvalue = '<a href="'.route('admin.organization.destroy', $this).'"
                            data-method="delete"
                            data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                            data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                            data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                            class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a>';
        return $returnvalue;
    }

    public function getRestoreButtonAttribute()
    {
        return '<a href="'.route('admin.organization.restore', $this).'" name="restore_user" class="btn btn-xs btn-info"><i class="fa fa-refresh" data-toggle="tooltip" data-placement="top" title="Restore Organization"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeletePermanentlyButtonAttribute()
    {
        return '<a href="'.route('admin.organization.delete-permanently', $this).'" name="delete_user_perm" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.delete_permanently').'"></i></a> ';
    }

    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.organization.show', $this).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.view').'"></i></a> ';
    }

    public function getApproveButtonAttribute()
    {
            switch ($this->approved) {
                case 0:
                    return '<a href="'.route('admin.organization.approve', [
                            $this,
                            1,
                        ]).'" class="btn btn-xs btn-success"><i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Approve"></i></a> ';
                // No break

                case 1:
                    return '<a href="'.route('admin.organization.approve', [
                            $this,
                            0,
                        ]).'" class="btn btn-xs btn-warning"><i class="fa fa-close" data-toggle="tooltip" data-placement="top" title="Reject"></i></a> ';
                // No break

                default:
                    return '';
                // No break
            }

        return '';
    }

    public function getStatusButtonAttribute()
    {
            switch ($this->status) {
                case 0:
                    return '<a href="'.route('admin.organization.mark', [
                            $this,
                            1,
                        ]).'" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.activate').'"></i></a> ';
                // No break

                case 1:
                    return '<a href="'.route('admin.organization.mark', [
                            $this,
                            0,
                        ]).'" class="btn btn-xs btn-warning"><i class="fa fa-pause" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.deactivate').'"></i></a> ';
                // No break

                default:
                    return '';
                // No break
            }

        return '';
    }
    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        if ($this->trashed()) {
            return $this->restore_button.$this->delete_permanently_button;
        }

        if($this->status == 0)
        {
            return
                $this->show_button.
                $this->edit_button.
                $this->status_button.
                $this->delete_button;
        }
        else
        {
            return
                $this->show_button.
                $this->edit_button.
                $this->approve_button.
                $this->status_button.
                $this->delete_button;
        }
    }

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

    public function getFullAddressAttribute()
    {
        if($this->address2 == "" || $this->address2 == null)
        {
            return $this->address1.', '.$this->city.' '.$this->zip.', '.$this->state.', '.$this->country;
        }
        else
        {
            return $this->address1.', '.$this->address2.', '.$this->city.' '.$this->zip.', '.$this->state.', '.$this->country;
        }

    }
}