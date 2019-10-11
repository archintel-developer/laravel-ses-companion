<?php


Route::resource('management', 'ArchintelDev\SesCompanion\Controllers\ManagementController');

Route::get('client/{uuid}', ['uses' => 'ArchintelDev\SesCompanion\Controllers\ManagementController@show_subscribers']);