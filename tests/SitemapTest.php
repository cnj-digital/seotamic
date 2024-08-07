<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Addon;
use Statamic\Facades\Config;

class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Addon::get('cnj/seotamic')->editions(['lite', 'pro']);
        Config::set('statamic.editions.addons', [
            'cnj/seotamic' => 'pro'
        ]);

    }

    public function test_it_returns_404_when_sitemap_is_disabled(): void
    {
        Config::set('seotamic.sitemap', false);

        $this
            ->get('/sitemap.xml')
            ->assertStatus(404);
    }

    public function test_it_returns_200_when_sitemap_is_enabled(): void
    {
        Config::set('seotamic.sitemap', true);

        $this
            ->get('/sitemap.xml')
            ->assertStatus(200);
    }

    public function test_it_returns_valid_xml(): void
    {
        $response = $this->get("/sitemap.xml");
        $response->assertOk(200);
    
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
    }

    public function test_it_returns_home(): void 
    {
        $this
            ->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/home');
    }

    public function test_it_excludes_protected_collections(): void
    {
        $this
            ->get('/sitemap.xml')
            ->assertOk(200)
            ->assertDontSee('/protected_collection/test');
    }

    public function test_it_excludes_protected_entities(): void
    {
        $this
            ->get('/sitemap.xml')
            ->assertOk(200)
            ->assertDontSee('/protected_entity');
    }
}
