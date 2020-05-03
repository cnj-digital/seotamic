<?php

namespace Cnj\Seomatic;

use Illuminate\Support\Facades\Session;
use Statamic\Facades\CP\Nav;
use Cnj\Seomatic\Tags\OpenGraph;
use Cnj\Seomatic\Tags\SeomaticTags;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__ . '/routes/cp.php',
    ];

    protected $tags = [
        SeomaticTags::class,
    ];

    public function boot()
    {
        parent::boot();

        // dd(Session::all());

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'seomatic');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'seomatic');

        Nav::extend(function ($nav) {
            $nav->content('SEOmatic')
                ->section('Tools')
                ->route('cnj.seomatic.index')
                ->icon('seo-search-graph');
        });
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../config/seomatic.php', 'seomatic');
    }
}
