<?php

Route::group([
    'namespace'  => 'Subadmin',
], function () {

    Route::group([
        'middleware' => ['access.routeNeedsRole:2', 'access.routeNeedsOwner', 'access.routeNeedsOrganization'],
    ], function () {
        Route::get('subadmin/pending', 'SubadminController@getPending')->name('subadmin.pending');
        Route::get('subadmin/deactivated', 'SubadminController@getDeactivated')->name('subadmin.deactivated');
        Route::get('subadmin/deleted', 'SubadminController@getDeleted')->name('subadmin.deleted');

        Route::resource('subadmin', 'SubadminController');
        Route::post('subadmin/get', 'SubadminTableController')->name('subadmin.get');

        Route::get('subadmin/mark/{subadmin}/{status}', 'SubadminController@mark')->name('subadmin.mark')->where(['status' => '[0,1]']);
        Route::get('subadmin/approve/{subadmin}/{approve}', 'SubadminController@approve')->name('subadmin.approve')->where(['approve' => '[0,1]']);

        Route::group(['prefix' => 'subadmin/{deletedSubadmin}'], function () {
            Route::get('delete', 'SubadminController@delete')->name('subadmin.delete-permanently');
            Route::get('restore', 'SubadminController@restore')->name('subadmin.restore');
        });
    });
});
