<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Cache;

class MetaRobotsTest extends TestCase
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

    public function test_robots_empty_when_global_and_entry_are_false(): void
    {
        // EN globals have robots_none: false, Contact entry has seotamic_robots_none: false
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertEquals('', $meta['robots']);
    }

    public function test_robots_noindex_when_entry_flag_is_true(): void
    {
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $entry->set('seotamic_robots_none', true);

        $meta = $entry->seotamic_meta;

        $this->assertEquals('nofollow,noindex', $meta['robots']);
    }

    public function test_robots_noindex_when_global_flag_is_true(): void
    {
        $globals = base_path('content/seotamic_en_US.yaml');
        $content = file_get_contents($globals);
        $content = str_replace('robots_none: false', 'robots_none: true', $content);
        file_put_contents($globals, $content);

        Cache::flush();

        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $meta = $entry->seotamic_meta;

        $this->assertEquals('nofollow,noindex', $meta['robots']);
    }

    public function test_global_robots_none_overrides_entry_setting(): void
    {
        $globals = base_path('content/seotamic_en_US.yaml');
        $content = file_get_contents($globals);
        $content = str_replace('robots_none: false', 'robots_none: true', $content);
        file_put_contents($globals, $content);

        Cache::flush();

        // Even with entry flag false, global true takes precedence
        $entry = Entry::find('7623f560-baef-45eb-9ece-818c6f30eaec');
        $entry->set('seotamic_robots_none', false);

        $meta = $entry->seotamic_meta;

        $this->assertEquals('nofollow,noindex', $meta['robots']);
    }
}
