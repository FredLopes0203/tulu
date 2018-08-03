<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Access\User\UserRepository;
use Illuminate\Support\Facades\File;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
//        $url = "";
//        if ($request->hasFile('logo')) {
//            $fileName = access()->user()->email . '.png';
//
//            $request->file('logo')->move(
//                base_path() . '/public/img/profile/', $fileName
//            );
//            $url = 'img/profile/' . $fileName;
//        }

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

        $output = $this->user->updateProfile(access()->id(), $request->only('first_name', 'last_name', 'email'), $url);


        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            access()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(trans('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
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
