<?php

namespace Cnj\Seotamic;

use Statamic\Facades\CP\Nav;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__ . '/routes/cp.php',
    ];

    protected $tags = [
        \Cnj\Seotamic\Tags\SeotamicTags::class,
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

        Event::subscribe(Subscriber::class);
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/seotamic.php', 'seotamic');
    }
}
