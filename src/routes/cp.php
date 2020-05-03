<?php

Route::middleware('web')->group(function() {
    Route::get('/cnj/seomatic/', 'SettingsController@index')->name('cnj.seomatic.index');
    Route::post('/cnj/seomatic/', 'SettingsController@update')->name('cnj.seomatic.update');
});
