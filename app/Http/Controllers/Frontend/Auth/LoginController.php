<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Helpers\Auth\Auth;
use App\Mail\RequestDemo;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Helpers\Frontend\Auth\Socialite;
//use Laravel\Socialite\Facades\Socialite;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Frontend\Access\User\UserSessionRepository;
use Illuminate\Support\Facades\Mail;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(homeRoute());
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    /**
     * @param Request $request
     * @param $user
     *
     * @throws GeneralException
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        /*
         * Check to see if the users account is confirmed and active
         */

        if( $user->roles->first()->name != "Admin" && $user->roles->first()->name != "SuperAdmin" )
        {
            access()->logout();
            throw new GeneralException(trans('auth.failed'));
        }
        else
        {
            if (! $user->isConfirmed()) {
                access()->logout();

                // If the user is pending (account approval is on)
                if ($user->isPending()) {
                    throw new GeneralException(trans('exceptions.frontend.auth.confirmation.pending'));
                }

                // Otherwise see if they want to resent the confirmation e-mail
                throw new GeneralException(trans('exceptions.frontend.auth.confirmation.resend', ['user_id' => $user->id]));
            } elseif (! $user->isActive()) {
                access()->logout();
                throw new GeneralException(trans('exceptions.frontend.auth.deactivated'));
            }
            else if($user->isadmin == 0)
            {
                access()->logout();
                throw new GeneralException(trans('auth.failed'));
            }
        }

        event(new UserLoggedIn($user));

        // If only allowed one session at a time
        if (config('access.users.single_login')) {
            app()->make(UserSessionRepository::class)->clearSessionExceptCurrent($user);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /*
         * Boilerplate needed logic
         */

        /*
         * Remove the socialite session variable if exists
         */
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }

        /*
         * Remove any session data from backend
         */
        app()->make(Auth::class)->flushTempSession();

        /*
         * Fire event, Log out user, Redirect
         */
        event(new UserLoggedOut($this->guard()->user()));

        /*
         * Laravel specific logic
         */
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutAs()
    {
        //If for some reason route is getting hit without someone already logged in
        if (! access()->user()) {
            return redirect()->route('frontend.auth.login');
        }

        //If admin id is set, relogin
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            //Save admin id
            $admin_id = session()->get('admin_user_id');

            app()->make(Auth::class)->flushTempSession();

            //Re-login admin
            access()->loginUsingId((int) $admin_id);

            //Redirect to backend user page
            return redirect()->route('admin.access.user.index');
        } else {
            app()->make(Auth::class)->flushTempSession();

            //Otherwise logout and redirect to login
            access()->logout();

            return redirect()->route('frontend.auth.login');
        }
    }

    public function handleProviderCallback()
    {
        /*try {
            $user = \Laravel\Socialite\Facades\Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            return redirect()->route('frontend.auth.login');
        }
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);*/
        //return \Laravel\Socialite\Facades\Socialite::driver('facebook')->redirect();

        return redirect()->route('frontend.user.dashboard');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function requestDemo(Request $request)
    {
        $name = $request->input('Name');
        $email = $request->input('Mail');
        $message = $request->input('Message');

        $adminContact = "kurt.nguyen@qnexis.com";

        Mail::to($adminContact)->send(new RequestDemo($email, $name, $message));

        return redirect('/');
    }
}