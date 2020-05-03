<?php

Route::middleware('web')->group(function() {
    Route::get('/cnj/seotamic/', 'SettingsController@index')->name('cnj.seotamic.index');
    Route::post('/cnj/seotamic/', 'SettingsController@update')->name('cnj.seotamic.update');
});
