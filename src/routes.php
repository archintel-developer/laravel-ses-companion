<?php

//package api
Route::prefix('/{slug}/{group}')->group(function () {
    Route::get('api/has/bounced/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@hasBounced');
    Route::get('api/has/complained/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@hasComplained');
    Route::get('api/stats/batch/{name}', 'ArchintelDev\SesCompanion\Controllers\StatsController@statsForBatch');
    Route::get('api/stats/email/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@statsForEmail');
});

Route::prefix('/sr')->group(function () {
    //user tracking
    Route::get('beacon/{beaconIdentifier}', 'ArchintelDev\SesCompanion\Controllers\SnsController@open');
    Route::get('link/{linkId}', 'ArchintelDev\SesCompanion\Controllers\SnsController@click');

    //recieve SNS notification
    Route::post('notification/bounce', 'ArchintelDev\SesCompanion\Controllers\SnsController@bounce');
    Route::post('notification/complaint', 'ArchintelDev\SesCompanion\Controllers\SnsController@complaint');
    Route::post('notification/delivery', 'ArchintelDev\SesCompanion\Controllers\SnsController@delivery');
});

Route::resource('management', 'ArchintelDev\SesCompanion\Controllers\ManagementController');
Route::get('client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_subscribers']);
Route::get('/client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_client_sub']);
Route::get('/dashboard', 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_dashboard')->name('dashboard');

Route::resource('group', 'ArchintelDev\SesCompanion\Controllers\GroupController');

Route::get('/account/{account}/{account_id}', 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_group');
Route::get('/group/{account_id}/{group}', 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_group_sub');
Route::get('/subscriber/{account}/{group}', 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_stat');

Route::get('/send-mail/{type}/{account}/{account_id}/{group?}', 'ArchintelDev\SesCompanion\Controllers\SnsController@send');

Route::get('/import-subscriber/{account}/{account_uuid}/{group}', 'ArchintelDev\SesCompanion\Controllers\ImportController@import_subscriber')->name('importUser');
Route::post('/importSubscriber', 'ArchintelDev\SesCompanion\Controllers\ImportController@importSubscriber')->name('importSub');