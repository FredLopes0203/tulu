<?php

use Illuminate\Http\Request;

Route::group(['middleware' => ['api','cors'], 'namespace' => 'Backend\Api'], function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['result' => false]);
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'ApiuserController@login');
        Route::post('register', 'ApiuserController@register');
        Route::post('resendverificationcode', 'ApiuserController@resendverificationcode');
        Route::post('verifycode', 'ApiuserController@verifycode');

        Route::post('forgotpassword', 'ApiuserController@sendResetPWDEmail');
        Route::post('resetcode', 'ApiuserController@ConfirmResetPWDcode');
        Route::post('resetpwd', 'ApiuserController@ResetPwd');

        Route::post('logout', 'ApiuserController@logout');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::post('load', 'ApiuserController@loadProfile');
        Route::post('save', 'ApiuserController@saveProfile');
        Route::post('savephoto', 'ApiuserController@saveProfilePicture');
        Route::post('assignorganization', 'ApiuserController@assignOrg');
        Route::post('updatelocation', 'ApiuserController@updateLocation');
    });

    Route::group(['prefix' => 'alert',  'middleware' => 'mobileAdmin'], function () {
        Route::post('load', 'ApiAlertController@loadAlert');
        Route::post('loadresponse', 'ApiAlertController@loadAlertResponse');
        Route::post('checkcreate', 'ApiAlertController@checkCreateAbility');
        Route::post('checkupdate', 'ApiAlertController@checkUpdateAbility');

        Route::post('createalert', 'ApiAlertController@createAlert');
        Route::post('updatealert', 'ApiAlertController@updateAlert');
        Route::post('dismissalert', 'ApiAlertController@dismissAlert');
    });

    Route::group(['prefix' => 'useralert',  'middleware' => 'mobileUser'], function () {
        Route::post('load', 'ApiAlertController@loadUserAlert');
        Route::post('response', 'ApiAlertController@responseUserAlert');
    });
});