<?php


Route::prefix('/{slug}')->group(function () {
    Route::get('beacon/{beaconIdentifier}', 'ArchintelDev\SesCompanion\Controllers\SnsController@open');
    Route::get('link/{linkId}', 'ArchintelDev\SesCompanion\Controllers\SnsController@click');

    Route::get('api/has/bounced/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@hasBounced');
    Route::get('api/has/complained/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@hasComplained');
    Route::get('api/stats/batch/{name}', 'ArchintelDev\SesCompanion\Controllers\StatsController@statsForBatch');
    Route::get('api/stats/email/{email}', 'ArchintelDev\SesCompanion\Controllers\StatsController@statsForEmail');
});
Route::prefix('/sr')->group(function () {
    // Route::post('notification/bounce', 'ArchintelDev\SesCompanion\Controllers\SnsController@bounce');
    // Route::post('notification/complaint', 'ArchintelDev\SesCompanion\Controllers\SnsController@complaint');
    // Route::post('notification/delivery', 'ArchintelDev\SesCompanion\Controllers\SnsController@delivery');

    Route::post('notification/bounce', 'ArchintelDev\LaravelSes\Controllers\BounceController@bounce');
    Route::post('notification/delivery', 'ArchintelDev\LaravelSes\Controllers\DeliveryController@delivery');
    Route::post('notification/complaint', 'ArchintelDev\LaravelSes\Controllers\ComplaintController@complaint');
});

Route::resource('management', 'ArchintelDev\SesCompanion\Controllers\ManagementController');
Route::get('client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_subscribers']);
Route::get('/client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_client_sub']);
// Route::get('/dashboard', 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_dashboard')->name('dashboard');

Route::get('/import-subscriber/{slug}/{uuid}', 'ArchintelDev\SesCompanion\Controllers\ImportController@import_subscriber')->name('importUser');
Route::post('/importSubscriber', 'ArchintelDev\SesCompanion\Controllers\ImportController@importSubscriber')->name('importSub');