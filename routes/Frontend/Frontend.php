<?php

/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', 'FrontendController@index')->name('index');

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {

        Route::get('account', 'AccountController@index')->name('account');
        Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    });
});