<?php

namespace App\Models;

use App\Models\Access\User\User;
/**
 * Class RoleAttribute.
 */
trait AlertAttribute
{
    public function getCreatorNameAttribute()
    {
        $creatorId = $this->creator;
        $creator = User::where('id', $creatorId)->first();

        $creatorName = "Unknown";
        if($creator != null)
        {
            $creatorName = '<img src="'.$creator->getPicture().'" class="img-circle" style="max-width: 60px;" alt="User Image" /><br>
                            Email: <b>'.$creator->email.'</b><br>
                            First Name: <b>'.$creator->first_name.'</b><br>
                            Last Name: <b>'.$creator->last_name.'</b>';
        }

        return $creatorName;
    }

    public function getTypeLabelAttribute()
    {
        $type = $this->alerttype;

        $typeStr = "";
        switch ($type)
        {
            case 1:
                $typeStr = '<b style="color: green">Create</b>';
                break;
            case 2:
                $typeStr = '<b style="color: blue">Update</b>';
                break;
            case 3:
                $typeStr = '<b style="color: red">Dismiss</b>';
                break;
            default:
                break;
        }

        return $typeStr;
    }
    /**
     * @return string
     */
//    public function getEditButtonAttribute()
//    {
//        $returnvalue = '<a href="'.route('admin.notification.edit', $this).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.edit').'"></i></a> ';
//        return $returnvalue;
//    }
//
//    /**
//     * @return string
//     */
//    public function getDeleteButtonAttribute()
//    {
//        $returnvalue = '<a href="'.route('admin.notification.destroy', $this).'"
//                            data-method="delete"
//                            data-trans-button-cancel="'.trans('buttons.general.cancel').'"
//                            data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
//                            data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
//                            class="btn btn-xs btn-danger"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a>';
//        return $returnvalue;
//    }
//
//    public function getStatusLabelAttribute()
//    {
//        if($this->status == 1)
//        {
//            return '<label class="label label-info">Pushed</label>';
//        }
//        else if($this->status == 2)
//        {
//            return '<label class="label label-warning">Planned</label>';
//        }
//
//        return '<label class="label label-danger">Error</label>';
//    }
//
//    /**
//     * @return string
//     */
//    public function getActionButtonsAttribute()
//    {
//        return $this->delete_button;
//    }
}