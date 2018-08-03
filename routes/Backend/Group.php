<?php

Route::group([
    'namespace'  => 'Group',
], function () {

    Route::group([
        'middleware' => 'access.routeNeedsRole:2',
    ], function () {
        Route::resource('group', 'GroupController');
        Route::get('/groupassign', 'GroupController@AssignShow')->name('group.assignshow');
        Route::post('/groupassign', 'GroupController@AssignGroup')->name('group.assign');
    });
});
