<?php

Route::group([
    'namespace'  => 'Myuser',
], function () {

    Route::group([
        'middleware' => ['access.routeNeedsRole:2', 'access.routeNeedsOrganization'],
    ], function () {

        Route::get('myuser/pending', 'MyuserController@getPending')->name('myuser.pending');
        Route::get('myuser/deactivated', 'MyuserController@getDeactivated')->name('myuser.deactivated');
        Route::get('myuser/deleted', 'MyuserController@getDeleted')->name('myuser.deleted');

        Route::resource('myuser', 'MyuserController');
        Route::post('getmyusers', 'MyuserTableController')->name('myusers.get');

        Route::get('myuser/mark/{user}/{status}', 'MyuserController@mark')->name('myuser.mark')->where(['status' => '[0,1]']);
        Route::get('myuser/approve/{user}/{approve}', 'MyuserController@approve')->name('myuser.approve')->where(['approve' => '[0,1]']);

        Route::group(['prefix' => 'myuser/{deletedUser}'], function () {
            Route::get('delete', 'MyuserController@delete')->name('myuser.delete-permanently');
            Route::get('restore', 'MyuserController@restore')->name('myuser.restore');
        });
    });
});

