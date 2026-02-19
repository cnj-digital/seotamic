<?php

use Cnj\Seotamic\Http\Controllers\SitemapController;

Route::get('sitemap.xml', SitemapController::class)->name('cnj.seotamic.sitemap');
