<?php

Route::group([
    'namespace'  => 'Organization',
], function () {

    Route::group([
        'middleware' => 'access.routeNeedsRole:1',
    ], function () {
        Route::get('organization/pending', 'OrganizationController@getPending')->name('organization.pending');
        Route::get('organization/deactivated', 'OrganizationController@getDeactivated')->name('organization.deactivated');
        Route::get('organization/deleted', 'OrganizationController@getDeleted')->name('organization.deleted');

        Route::resource('organization', 'OrganizationController');
        Route::post('getorganizations', 'OrganizationTableController')->name('organizations.get');

        Route::get('organization/mark/{group}/{status}', 'OrganizationController@mark')->name('organization.mark')->where(['status' => '[0,1]']);
        Route::get('organization/approve/{group}/{approve}', 'OrganizationController@approve')->name('organization.approve')->where(['approve' => '[0,1]']);

        Route::group(['prefix' => 'organization/{deletedOrganization}'], function () {
            Route::get('delete', 'OrganizationController@delete')->name('organization.delete-permanently');
            Route::get('restore', 'OrganizationController@restore')->name('organization.restore');
        });
    });
});
