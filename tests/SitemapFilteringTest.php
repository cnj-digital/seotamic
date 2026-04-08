<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Addon;
use Statamic\Facades\Config;
use Illuminate\Support\Facades\Cache;

class SitemapFilteringTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Addon::get('cnj/seotamic')->editions(['lite', 'pro']);
        Config::set('statamic.editions.addons', [
            'cnj/seotamic' => 'pro'
        ]);

        Cache::flush();
    }

    public function test_sitemap_includes_published_entries(): void
    {
        $this
            ->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/home');
    }

    public function test_sitemap_returns_404_for_lite_edition(): void
    {
        Config::set('statamic.editions.addons', [
            'cnj/seotamic' => 'lite'
        ]);

        $this
            ->get('/sitemap.xml')
            ->assertStatus(404);
    }

    public function test_sitemap_uses_headless_mode_url(): void
    {
        config(['seotamic.headless_mode' => 'https://frontend.example.com']);

        Cache::flush();

        $this
            ->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('https://frontend.example.com/');
    }
}
