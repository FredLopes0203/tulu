<?php

namespace App\Http\Controllers\Backend\Subadmin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Subadmin\Subadmin;
use App\Notifications\Backend\Access\AccountCreated;
use App\Repositories\Backend\Subadmin\SubadminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Repositories\Backend\Access\Role\RoleRepository;

class SubadminController extends Controller
{
    protected $subadmins;

    public function __construct(SubadminRepository $subadminRepository)
    {
        $this->subadmins = $subadminRepository;
    }

    public function create(Request $request)
    {
        return view('backend.subadmin.create');
    }

    public function index(Request $request)
    {
        return view('backend.subadmin.index');
    }

    public function getPending(Request $request)
    {
        return view('backend.subadmin.pending');
    }

    public function getDeactivated(Request $request)
    {
        return view('backend.subadmin.deactivated');
    }

    public function getDeleted(Request $request)
    {
        return view('backend.subadmin.deleted');
    }

    public function store(Request $request)
    {
        $subadminemail = $request->input('email');

        $url = "";
        $imageData = $request->input('imgData');

        if($imageData != null && $imageData != "")
        {
            $num = $this->generateRndString();
            $email = str_replace(' ', '', $subadminemail);
            $fileName = $email . '_'.$num.'png';
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            File::put(base_path() . '/public/img/profile/'.$fileName, base64_decode($imageData));
            $url = 'img/profile/' . $fileName;
        }

        $this->subadmins->createSubadmin(
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



        return redirect()->route( 'admin.subadmin.index')->withFlashSuccess('Subadmin Created Successfully.');
    }

    public function show(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        return view('backend.subadmin.show')
            ->withSubadmin($subadmin);
    }

    public function destroy(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->subadmins->delete($subadmin);
        return redirect()->route('admin.subadmin.deleted')->withFlashSuccess('Subadmin Deleted Successfully.');
    }

    public function delete(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        $this->subadmins->forceDelete($subadmin);
        return redirect()->route('admin.subadmin.deleted')->withFlashSuccess('Subadmin Deleted Permanently.');
    }

    public function restore(Subadmin $subadmin, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }
        else
        {
            $this->subadmins->restore($subadmin);
            $status = $subadmin->status;
            $approve = $subadmin->approve;

            return redirect()->route($status == 1 ? $approve == 1 ? 'admin.subadmin.index': 'admin.subadmin.pending' : 'admin.subadmin.deactivated')->withFlashSuccess('Subadmin Restored Successfully.');
        }
    }

    public function mark(Subadmin $subadmin, $status, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $approve = $subadmin->approve;
        $this->subadmins->mark($subadmin, $status);
        return redirect()->route($status == 1 ? $approve == 1 ? 'admin.subadmin.index': 'admin.subadmin.pending' : 'admin.subadmin.deactivated')->withFlashSuccess(trans('alerts.backend.users.updated'));
    }

    public function approve(Subadmin $subadmin, $approve, Request $request)
    {
        if(!$this->subadmins->CheckIfMySubadmin($subadmin))
        {
            throw new GeneralException(trans('auth.general_error'));
        }

        $this->subadmins->approve($subadmin, $approve);
        return redirect()->route($approve == 1 ? 'admin.subadmin.index' : 'admin.subadmin.pending')->withFlashSuccess(trans('alerts.backend.users.updated'));
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
