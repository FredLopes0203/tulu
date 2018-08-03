<?php

Route::group([
    'prefix'     => 'alert',
    'as'         => 'alert.',
    'namespace'  => 'Alert',
], function () {
    Route::group([
        'middleware' => ['access.routeNeedsRole:2', 'access.routeNeedsOrganization'],
    ], function () {
        Route::group(['namespace' => 'Curalert'], function () {
            Route::post('getalert', 'CuralertTableController')->name('alerts.get');
            Route::post('getresponse', 'CurResponseTableController')->name('responses.get');

            Route::get('curalert/index', 'CuralertController@index')->name('curalert.index');

            Route::get('create', 'CuralertController@create')->name('curalert.create');
            Route::post('newalert', 'CuralertController@newalert')->name('curalert.newalert');

            Route::get('update', 'CuralertController@update')->name('curalert.update');
            Route::post('store', 'CuralertController@store')->name('curalert.store');

            Route::get('dismiss', 'CuralertController@dismiss')->name('curalert.dismiss');
            Route::post('destroy', 'CuralertController@destroy')->name('curalert.destroy');
        });

        Route::group(['namespace' => 'History'], function () {
            Route::get('history/index', 'AlerthistoryController@index')->name('history.index');
            Route::get('history/{alertId}', 'AlerthistoryController@view')->name('history.view');
            Route::post('getalertlist', 'AlertTableController')->name('history.get');
            Route::post('getalerthistorylist', 'AlertdetailTableController')->name('history.detail.get');
        });
    });
});
