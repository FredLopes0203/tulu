<?php

namespace App\Http\Controllers\Backend\Api;

use App\Mail\AdminApproval;
use App\Mail\UserApproval;
use App\Models\Access\User\SocialLogin;
use App\Models\Group;
use App\Models\UserLocation;
use App\Notifications\Frontend\Auth\UserNeedsCodeverification;
use App\Notifications\Frontend\Auth\UserNeedsResetCode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Models\Access\User\User;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\Role\RoleRepository;
use App\Repositories\Backend\Access\User\UserRepository;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use Illuminate\Support\Facades\Mail;

/**
 * Class UserController.
 */
class ApiuserController extends Controller
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

    public function create(Request $request)
    {

    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try
        {
            $token = JWTAuth::attempt($credentials);
            if(!$token)
            {
                return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
            }
        }
        catch (JWTException $e)
        {
            throw new HttpException(500);
        }
        $user = JWTAuth::toUser($token);

        if($user->status == 0)
        {
            return response()->json(['result' => false, 'message' => 'Your account was deactivated. Contact to the administrator!']);
        }

        if($user->confirmed == 0)
        {
            return response()->json(['result' => false, 'message' => 'Your email address is not confirmed yet.', 'code' => 'notconfirmed']);
        }

        $fcm_token = "";
        if($user->isadmin == 0)
        {
            $fcmtoken = $request->get('fcmtoken');

            if($fcmtoken != "" && $fcmtoken != null)
            {
                $fcm_token = $fcmtoken;
            }
        }

        $user->fcmtoken = $fcm_token;
        $user->save();

        return response()->json([
                'result' => true,
                'token' => $token,
                'userInfo' => $user
            ]);
    }

    public function logout(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => $token.'Incorrect Credential Info.']);
        }

        $user->fcmtoken = "";
        $user->save();

        return response()->json(['result' => true, 'userInfo' => $user]);
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $password = $input['password'];
        $password = Hash::make($password);
        $userType = $input['usertype'];

        $fcm_token = "";

        if($userType == 0)
        {
            $fcmtoken = $input['fcmtoken'];

            if($fcmtoken != "" && $fcmtoken != null)
            {
                $fcm_token = $fcmtoken;
            }
        }

        $isAdmin = 0;
        if($userType == 1)
        {
            $isAdmin = 1;
        }

        try
        {
            $user = User::create([
                'first_name' => $input['firstname'],
                'last_name' => $input['lastname'],
                'email' => $input['email'],
                'password' => $password,
                'phonenumber' => $input['phonenumber'],
                'isadmin' => $isAdmin,
                'status' => 1,
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => 0,
                'verificationcode' => $this->generateRndString(),
                'fcmtoken' => $fcm_token
            ]);

            if($userType == 1)
            {
                $user->attachRoles(2);
            }
            else if($userType == 2)
            {
                $user->attachRoles(3);
            }

            $user->notify(new UserNeedsCodeverification($user->verificationcode));

            $credentials = $request->only('email', 'password');

            try
            {
                $token = JWTAuth::attempt($credentials);
                if(!$token)
                {
                    return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
                }
            }
            catch (JWTException $e)
            {
                throw new HttpException(500);
            }

            return response()->json(['result'=>true, 'token' => $token]);
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            $errCode = $e->errorInfo[1];
            if($errCode == 1062)
            {
                return response()->json(['result'=>false, 'message'=>'Duplicated email address.']);
            }
            else
            {
                return response()->json(['result'=>false, 'message'=>'Error during register user']);
            }
        }
    }

    function loadProfile(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        $fcmtoken = $input['fcmtoken'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $fcm_token = "";
        if($user->isadmin == 0)
        {
            if($fcmtoken != "" && $fcmtoken != null)
            {
                $fcm_token = $fcmtoken;
            }
        }

        $user->fcmtoken = $fcm_token;
        $user->save();

        return response()->json(['result' => true, 'userInfo' => $user]);
    }

    function saveProfile(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];
        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result'=>false, 'message'=>'Token Required.']);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Expired.']);

        } catch (TokenInvalidException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Invalid']);
        }

        if(!$user)
        {
            return response()->json(['result'=>false, 'message'=>'Invalid user info.']);
        }

        $firstname = $input['firstname'];
        $lastname = $input['lastname'];
        $phonenumber = $input['phonenumber'];

        if($firstname != "")
        {
            $user->first_name = $firstname;
        }

        if($lastname != "")
        {
            $user->last_name = $lastname;
        }

        if($phonenumber != "")
        {
            $user->phonenumber = $phonenumber;
        }

        $user->save();

        return response()->json(['result' => true]);
    }

    public function saveProfilePicture(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result'=>false, 'message'=>'Token Required.']);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Expired.']);

        } catch (TokenInvalidException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Invalid']);
        }

        if(!$user)
        {
            return response()->json(['result'=>false, 'message'=>'Invalid user info.']);
        }

        $url = "";

        if ($request->hasFile('profileimg')) {
            $num = $this->generateRndString();
            $fileName = $user->email . '_'.$num.'.png';

            $request->file('profileimg')->move(
                base_path() . '/public/img/profile/', $fileName
            );
            $url = 'img/profile/' . $fileName;
        }

        if($url != "")
        {
            $user->profile_picture = $url;
            $user->save();
        }
        return response()->json(['result' => true, 'url' => $url]);
    }

    public function assignOrg(Request $request)
    {
        $input = $request->all();
        $orgId = $input['orgID'];

        $token = $input['token'];
        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result'=>false, 'message'=>'Token Required.']);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Expired.']);

        } catch (TokenInvalidException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Invalid']);
        }

        if(!$user)
        {
            return response()->json(['result'=>false, 'message'=>'Invalid user info.']);
        }

        $groupInfo = Group::where('groupid', $orgId)->first();

        if($groupInfo == null)
        {
            return response()->json(['result' => false, 'message' => 'Invalid organization ID#']);
        }
        else
        {
            if($groupInfo->approved == 0)
            {
                return response()->json(['result' => false, 'message' => 'Not approved organization.']);
            }
            else if($groupInfo->status == 0)
            {
                return response()->json(['result' => false, 'message' => 'Inactivated organization']);
            }

            $user->organization = $groupInfo->id;
            $user->save();

            //$adminContact = "kurt.nguyen@qnexis.com";
            if($user->isadmin == 1)
            {
                $adminContact = "kurt.nguyen@qnexis.com";
                Mail::to($adminContact)->send(new AdminApproval($user, $groupInfo));

                $initialAdmin = User::where('status', 1)
                    ->where('approve', 1)
                    ->where('isadmin', 1)
                    ->where('isinitial', 1)
                    ->where('organization', $groupInfo->id)
                    ->first();
                if($initialAdmin != null)
                {
                    $initialadminContact = $initialAdmin->email;
                    Mail::to($initialadminContact)->send(new AdminApproval($user, $groupInfo));
                }
            }
            else
            {
                $adminContact = "kurt.nguyen@qnexis.com";
                Mail::to($adminContact)->send(new UserApproval($user, $groupInfo));

                $admins = User::where('status', 1)
                    ->where('approve', 1)
                    ->where('isadmin', 1)
                    ->where('organization', $groupInfo->id)
                    ->get();

                if($admins->count() > 0)
                {
                    foreach ($admins as $admin)
                    {
                        $adminAddress = $admin->email;
                        Mail::to($adminAddress)->send(new UserApproval($user, $groupInfo));
                    }
                }
            }

            return response()->json(['result' => true, 'groupName' => $groupInfo->name]);
        }
    }

    public function resendverificationcode(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];

        $existUser = User::where('email', $email)->first();
        if($existUser == null)
        {
            return response()->json(['result' => false, 'message' => 'Not registered user.']);
        }

        $verificationCode = $this->generateRndString();
        $existUser->verificationcode = $verificationCode;
        $existUser->save();

        $existUser->notify(new UserNeedsCodeverification($verificationCode));

        return response()->json(['result' => true]);
    }

    public function verifycode(Request $request)
    {
        $input = $request->all();
        $verificationCode = $input['verificationCode'];
        $email = $input['email'];

        $existUser = User::where('email', $email)->first();
        if($existUser == null)
        {
            return response()->json(['result' => false, 'message' => 'Not registered user.']);
        }

        if($verificationCode == $existUser->verificationcode)
        {
            $existUser->confirmed = 1;
            $existUser->save();

            $token = JWTAuth::fromUser($existUser);

            return response()->json(['result' => true, 'token' => $token, 'userInfo' => $existUser]);
        }
        else
        {
            return response()->json(['result' => false, 'message' => 'Invalid Code!']);
        }
    }

    public function sendResetPWDEmail(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];

        $exist = User::where('email', $email)->first();
        if($exist == null)
        {
            return response()->json(['result' => false, 'message' => 'Not registered email.']);
        }

        $resetcode = $this->generatePwdResetString();
        $exist->resetcode = $resetcode;
        $exist->save();

        $exist->notify(new UserNeedsResetCode($exist->resetcode));

        return response()->json(['result' => true, 'message' => 'success']);
    }

    public function ConfirmResetPWDcode(Request $request)
    {
        $input = $request->all();
        $resetcode = $input['resetcode'];
        $email = $input['email'];

        $user = User::where('email', $email)->first();
        if($user == null)
        {
            return response()->json(['result' => false, 'message' => 'Not registered user.']);
        }

        $code = $user->resetcode;
        if($resetcode == $code)
        {
            $startTime = Carbon::parse($user->updated_at);
            $finishTime = Carbon::now();
            $totalDuration = $finishTime->diffInSeconds($startTime);
            $lastedMin = gmdate('i', $totalDuration);
            if(intval($lastedMin) >= 5)
            {
                $user->resetcode = "";
                $user->save();
                return response()->json(['result' => false, 'message' => 'Expired Code.\n Please try to send email again.', 'returncode' => '1']);
            }
            return response()->json(['result' => true, 'message' => 'success']);
        }
        return response()->json(['result' => false, 'message' => 'Invalid Code!']);
    }

    public function ResetPwd(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];
        $password = $input['password'];
        $user = User::where('email', $email)->first();
        if($user == null)
        {
            return response()->json(['result' => false, 'message' => 'Reset password failed.']);
        }
        else
        {
            $user->password = Hash::make($password);
            $user->resetcode = "";
            $user->save();
            return response()->json(['result' => true, 'message' => 'success']);
        }
    }

    function generatePwdResetString($length = 5) {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function MonNumToStr($month)
    {
        $retstr = "";

        switch ($month)
        {
            case "01":
                $retstr = "Jan";
                break;
            case "02":
                $retstr = "Feb";
                break;
            case "03":
                $retstr = "Mar";
                break;
            case "04":
                $retstr = "Apr";
                break;
            case "05":
                $retstr = "May";
                break;
            case "06":
                $retstr = "Jun";
                break;
            case "07":
                $retstr = "Jul";
                break;
            case "08":
                $retstr = "Aug";
                break;
            case "09":
                $retstr = "Sep";
                break;
            case "10":
                $retstr = "Oct";
                break;
            case "11":
                $retstr = "Nov";
                break;
            case "12":
                $retstr = "Dec";
                break;
            default:
                break;
        }
        return $retstr;
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

    function updateLocation(Request $request)
    {
        $input = $request->all();

        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result'=>false, 'message'=>'Token Required.']);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Expired.']);

        } catch (TokenInvalidException $e) {
            return response()->json(['result'=>false, 'message'=>'Token Invalid']);
        }

        $lat = $input['lat'];
        $long = $input['lng'];

        $existLocation = UserLocation::where('userid', $user->id)->first();
        if($existLocation != null)
        {
            $existLocation->lat = $lat;
            $existLocation->lng = $long;
            $existLocation->save();
        }
        else
        {
            $newLocation = UserLocation::create([
               'userid' => $user->id,
               'lat' => $lat,
               'lng' => $long
            ]);
        }

        return response()->json(['result'=>true]);
    }
}
