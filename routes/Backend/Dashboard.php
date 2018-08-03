<?php

/**
 * All route names are prefixed with 'admin.'.
 */

Route::group([
    'middleware' => ['access.routeNeedsOrganization'],
], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});



