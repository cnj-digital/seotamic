<?php

use Cnj\Seotamic\Http\Controllers\SettingsController;

Route::middleware('statamic.cp.authenticated')->group(function () {
    Route::get('/cnj/seotamic/', [SettingsController::class, 'index'])->name('cnj.seotamic.index');
    Route::post('/cnj/seotamic/', [SettingsController::class, 'update'])->name('cnj.seotamic.update');
});
