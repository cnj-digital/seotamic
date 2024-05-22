<?php

namespace Cnj\Seotamic\Tests;

use Statamic\Facades\Entry;

class MetaTest extends TestCase
{
    public function test_meta_has_all_the_expected_keys(): void
    {
        $entry = Entry::find('home');

        $this->assertArrayHasKey('title', $entry->seotamic_meta);
        $this->assertArrayHasKey('description', $entry->seotamic_meta);
        $this->assertArrayHasKey('canonical', $entry->seotamic_meta);
        $this->assertArrayHasKey('robots', $entry->seotamic_meta);
        $this->assertArrayHasKey('related', $entry->seotamic_meta);
    }
}
