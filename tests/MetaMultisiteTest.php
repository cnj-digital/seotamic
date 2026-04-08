<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class MetaMultisiteTest extends MultisiteTestCase
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

    public function test_meta_uses_entry_locale_globals(): void
    {
        $en = Entry::find('home');
        $sl = Entry::find('ab5cd389-070f-41b3-a004-e2861d1fc325');

        $this->assertNotNull($sl, 'Slovenian home entry not found');
        $this->assertEquals('sl_SI', $sl->site()->locale());

        $this->assertStringContainsString('- Something', $en->seotamic_meta['title']);
        $this->assertStringContainsString('- Nekaj', $sl->seotamic_meta['title']);
        $this->assertStringNotContainsString('- Something', $sl->seotamic_meta['title']);
    }
}
