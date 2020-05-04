<?php

namespace Cnj\Seotamic;

use Illuminate\Support\Facades\Session;
use Statamic\Facades\CP\Nav;
use Cnj\Seotamic\Tags\OpenGraph;
use Cnj\Seotamic\Tags\SeotamicTags;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__ . '/routes/cp.php',
    ];

    protected $tags = [
        SeotamicTags::class,
    ];

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'seotamic');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'seotamic');

        Nav::extend(function ($nav) {
            $nav->content('SEOtamic')
                ->section('Tools')
                ->route('cnj.seotamic.index')
                ->icon('seo-search-graph');
        });

        $this->publishes([
            __DIR__.'/../config/seotamic.php' => config_path('seotamic.php')
        ], 'config');
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/seotamic.php', 'seotamic');
    }
}
