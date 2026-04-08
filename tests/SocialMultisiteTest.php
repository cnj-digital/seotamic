<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class SocialMultisiteTest extends MultisiteTestCase
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

    public function test_social_uses_entry_locale_globals(): void
    {
        $en = Entry::find('home');
        $sl = Entry::find('ab5cd389-070f-41b3-a004-e2861d1fc325');

        $this->assertNotNull($sl, 'Slovenian home entry not found');
        $this->assertEquals('Demo Site', $en->seotamic_social['site_name']);
        $this->assertEquals('Demo Stran', $sl->seotamic_social['site_name']);
    }
}
