<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Addon;
use Statamic\Facades\Config;
use Statamic\Facades\Entry;

class EditionGatingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->copyFixtureGlobals();
    }

    protected function tearDown(): void
    {
        $this->cleanupFixtureGlobals();

        parent::tearDown();
    }

    public function test_meta_augment_returns_data_on_non_api_request(): void
    {
        $entry = Entry::find('home');
        $meta = $entry->seotamic_meta;

        $this->assertNotEmpty($meta);
        $this->assertArrayHasKey('title', $meta);
        $this->assertArrayHasKey('canonical', $meta);
    }

    public function test_social_augment_returns_data_on_non_api_request(): void
    {
        $entry = Entry::find('home');
        $social = $entry->seotamic_social;

        $this->assertNotEmpty($social);
        $this->assertArrayHasKey('title', $social);
        $this->assertArrayHasKey('image', $social);
    }

    public function test_meta_augment_returns_empty_for_lite_on_api_path(): void
    {
        Config::set('statamic.api.route', 'api');

        // Make a request to set the current request path to an API route
        $this->get('/api/test');

        $entry = Entry::find('home');
        $meta = $entry->seotamic_meta;

        $this->assertEmpty($meta);
    }

    public function test_meta_augment_returns_data_for_pro_on_api_path(): void
    {
        Config::set('statamic.api.route', 'api');
        Addon::get('cnj/seotamic')->editions(['lite', 'pro']);
        Config::set('statamic.editions.addons', [
            'cnj/seotamic' => 'pro'
        ]);

        $this->get('/api/test');

        $entry = Entry::find('home');
        $meta = $entry->seotamic_meta;

        $this->assertNotEmpty($meta);
        $this->assertArrayHasKey('title', $meta);
    }
}
