<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class MetaCanonicalTest extends TestCase
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

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // getCanonical() uses env('APP_URL') directly
        putenv('APP_URL=http://localhost');
    }

    protected function tearDownEnvironment(): void
    {
        putenv('APP_URL');
    }

    public function test_canonical_uses_app_url_and_entry_uri(): void
    {
        $entry = Entry::find('home');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertStringStartsWith('http://localhost', $canonical);
        $this->assertStringEndsWith($entry->uri(), $canonical);
    }

    public function test_canonical_uses_headless_mode_url(): void
    {
        config(['seotamic.headless_mode' => 'https://frontend.example.com']);

        $entry = Entry::find('home');
        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertStringStartsWith('https://frontend.example.com', $canonical);
        $this->assertStringEndsWith($entry->uri(), $canonical);
    }

    public function test_canonical_strips_trailing_slash_from_base_url(): void
    {
        config(['seotamic.headless_mode' => 'https://frontend.example.com/']);

        $entry = Entry::find('home');
        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertStringStartsWith('https://frontend.example.com/', $canonical);
        // No double slashes after protocol
        $this->assertStringNotContainsString('//', substr($canonical, 8));
    }

    public function test_canonical_with_custom_absolute_url(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_canonical', 'https://external.example.com/custom-page');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertEquals('https://external.example.com/custom-page', $canonical);
    }

    public function test_canonical_with_custom_relative_path_with_slash(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_canonical', '/custom-page');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertEquals('http://localhost/custom-page', $canonical);
    }

    public function test_canonical_with_custom_relative_path_without_slash(): void
    {
        $entry = Entry::find('home');
        $entry->set('seotamic_canonical', 'custom-page');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertEquals('http://localhost/custom-page', $canonical);
    }

    public function test_canonical_with_headless_mode_and_custom_relative_path(): void
    {
        config(['seotamic.headless_mode' => 'https://frontend.example.com']);

        $entry = Entry::find('home');
        $entry->set('seotamic_canonical', '/custom-page');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertEquals('https://frontend.example.com/custom-page', $canonical);
    }

    public function test_canonical_defaults_to_uri_when_no_custom_set(): void
    {
        $entry = Entry::find('home');

        $canonical = $entry->seotamic_meta['canonical'];

        $this->assertStringEndsWith($entry->uri(), $canonical);
    }
}
